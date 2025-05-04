<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CanceledOrderDataTable;
use App\DataTables\DeliveredOrderDataTable;
use App\DataTables\DroppedOffOrderDataTable;
use App\DataTables\OrderDataTable;
use App\DataTables\OutForDeliveryOffOrderDataTable;
use App\DataTables\PendingOrderDataTable;
use App\DataTables\ProcessedOrderDataTable;
use App\DataTables\ReceivedOrderDataTable;
use App\DataTables\ShippedOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProductVariantCombination;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Traits\TrelloTrait;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use TrelloTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pendingOrders(PendingOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.pending-order');
    }
    public function processedOrders(ProcessedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.processed-order');
    }
    public function droppedOffOrders(DroppedOffOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.dropped-off-order');
    }
    public function outForDeliveryOrders(OutForDeliveryOffOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.out-for-delivery-order');
    }
    public function shippedOrders(ShippedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.shipped-order');
    }
    public function deliveredOrders(DeliveredOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.delivered-order');
    }
    public function receivedOrders(ReceivedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.received-order');
    }
    public function canceledOrders(CanceledOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.canceled-orders-order');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['orderProducts.product', 'statusHistories' => function ($query) {
            $query->orderBy('changed_at', 'desc');
        }])->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        // delete order products
        $order->orderProducts()->delete();
        // delete transaction
        $order->transaction()->delete();

        $order->delete();

        return response(['status' => 'success', 'message' => 'Đã xóa thành công!']);
    }
    public function changeOrderStatus(Request $request)
    {
        // Tìm đơn hàng
        // $order = Order::findOrFail($request->id);
        $order = Order::with('orderProducts')->find($request->id);

        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'processed_and_ready_to_ship' => 'Đã xử lý - sẵn sàng giao',
            'dropped_off' => 'Người bán đóng gói',
            'shipped' => 'Người giao đã lấy hàng',
            'delivered' => 'Đã giao hàng',
            'received' => 'Khách đã nhận hàng',
            'canceled' => 'Đã hủy',
        ];
        $newStatus = $request->status;
        $currentStatus = $order->order_status;
        $reason = $request->cancel_reason ?? null; // Lấy lý do hủy đơn (nếu có)
        $reason2 = $request->delivered_reason ?? null; // Lấy lý do hủy đơn (nếu có)

        // Các trạng thái không thể thay đổi
        $immutableStatuses = ['canceled', 'received'];
        if (in_array($currentStatus, $immutableStatuses)) {
            return response()->json([
                'status'  => 'error',
                'message' => "Đơn hàng đã ở trạng thái '{$statusLabels[$currentStatus]}' và không thể thay đổi."
            ], 400);
        }

        // Các trạng thái hợp lệ (không cho phép nhảy cóc)
        $validTransitions = [
            'pending' => ['processed_and_ready_to_ship', 'canceled'],
            'processed_and_ready_to_ship' => ['dropped_off', 'canceled'],
            'dropped_off' => ['shipped'],
            'shipped' => ['delivered'],
            'delivered' => ['received'],
        ];

        // Kiểm tra nếu trạng thái mới không hợp lệ dựa trên trạng thái hiện tại
        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return response()->json([
                'status' => 'error',
                'message' => "Thay đổi trạng thái đơn hàng phải theo trình tự. Chuyển đổi trạng thái không hợp lệ từ '{$statusLabels[$currentStatus]}' sang '{$statusLabels[$newStatus]}'."
            ], 400);
        }

        // Nếu trạng thái mới là "canceled", bắt buộc nhập lý do
        if ($newStatus === 'canceled') {
            if (empty($reason)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp lý do hủy đơn hàng!'
                ], 400);
            }

            // Không thể hủy nếu đơn hàng đã được vận chuyển hoặc giao thành công
            if (in_array($currentStatus, ['shipped', 'out_for_delivery', 'delivered'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể hủy đơn hàng đã được vận chuyển hoặc giao hàng.'
                ], 400);
            }
            // ✅ Cập nhật lại tồn kho sản phẩm & biến thể
            foreach ($order->orderProducts as $orderProduct) {
                // Tăng số lượng sản phẩm chính
                DB::table('products')
                    ->where('id', $orderProduct->product_id)
                    ->increment('qty', $orderProduct->qty);

                // Nếu có biến thể, tăng số lượng cho biến thể
                if (!empty($orderProduct->variants) && $orderProduct->variants !== '[]') {
                    DB::table('product_variant_combinations')
                        ->where('id', $orderProduct->variants)
                        ->increment('quantity', $orderProduct->qty);
                }
            }
            // Nếu đơn hàng thanh toán qua thẻ/ví (không phải COD), cập nhật trạng thái thanh toán
            if ($order->payment_method != 'COD' && $order->payment_status == 1) {
                $order->payment_status = 0;
                // Gửi task hoàn tiền lên Trello
                $this->createTrelloRefundTask($order, $request->cancel_reason);
            }
        }
        // Nếu trạng thái mới là "delivered", bắt buộc nhập lý do
        if ($newStatus === 'delivered') {
            if (empty($reason2)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp lý do tại sao đơn hàng được đánh dấu là đã giao.'
                ], 400);
            }
            // Cập nhật trạng thái đơn hàng thành "delivered"
            $order->order_status = 'delivered';
            $order->save();

            // Lưu lý do giao hàng vào bảng order_status_histories
            DB::table('order_status_histories')->insert([
                'order_id' => $order->id,
                'status' => 'delivered',
                'updated_by' => auth()->id(),
                'changed_at' => now(),
                'reason' => $reason2,  // Lý do giao hàng
            ]);
        }
        // Cập nhật trạng thái đơn hàng
        $order->update([
            'order_status' => $newStatus,
            'payment_status' => $order->payment_status, // Giữ nguyên nếu không bị thay đổi
        ]);

        // Lưu lịch sử thay đổi trạng thái
        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $newStatus,
            'reason'     => $reason,
            'updated_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Trạng thái đơn hàng được cập nhật thành công'
        ]);
    }

    public function changePaymentStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        // Nếu đơn hàng đã hoàn tiền rồi thì không cho cập nhật lại thành trạng thái khác
        if ($order->payment_status == 2 && in_array($request->status, [0, 1])) {
            return response([
                'status' => 'error',
                'message' => 'Không thể thay đổi trạng thái vì đơn hàng đã được hoàn tiền.'
            ], 400);
        }
        // Nếu trạng thái thanh toán đã là "Completed" thì không cho phép cập nhật lại thành "Pending"
        if ($order->payment_status == 1 && $request->status == 0) {
            return response([
                'status' => 'error',
                'message' => 'Không thể hoàn lại trạng thái thanh toán từ đã hoàn thành sang đang chờ xử lý'
            ]);
        }
        // Nếu đơn hàng chưa được giao (Delivered) hoặc chưa có xác nhận (Received), không thể đặt Payment Status = Completed
        if ($request->status == 1 && !in_array($order->order_status, ['delivered', 'received'])) {
            return response([
                'status' => 'error',
                'message' => 'Không thể đánh dấu thanh toán là đã hoàn tất cho các đơn hàng COD trước khi đơn hàng được giao hoặc nhận'
            ], 400);
        }
        if ($request->status == 2 && !in_array($order->order_status, ['canceled']) && $order->payment_method !== 'COD') {
            return response([
                'status' => 'error',
                'message' => 'Chỉ được hoàn tiền cho đơn hàng đã bị hủy và không thể hoàn tiền cho đơn hàng COD.'

            ], 400);
        }

        // Cập nhật trạng thái nếu hợp lệ
        $order->payment_status = $request->status;
        $order->save();

        return response([
            'status' => 'success',
            'message' => 'Đã cập nhật trạng thái thanh toán'
        ]);
    }
}
