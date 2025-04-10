<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\DataTables\OutForDeliveryOffOrderDataTable;
use App\DataTables\PendingOrderDataTable;
use App\DataTables\ProcessedOrderDataTable;
use App\DataTables\ReceivedOrderDataTable;
use App\DataTables\ShippedOrderDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Traits\TrelloTrait;

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

        return response(['status' => 'success', 'message' => 'Deleted successfully!']);
    }
    public function changeOrderStatus(Request $request)
    {
        // Tìm đơn hàng
        $order = Order::findOrFail($request->id);
        $newStatus = $request->status;
        $currentStatus = $order->order_status;
        $reason = $request->cancel_reason ?? null; // Lấy lý do hủy đơn (nếu có)

        // Các trạng thái không thể thay đổi
        $immutableStatuses = ['canceled', 'received'];
        if (in_array($currentStatus, $immutableStatuses)) {
            return response()->json([
                'status'  => 'error',
                'message' => "Order has already been $currentStatus and cannot be changed."
            ], 400);
        }

        // Các trạng thái hợp lệ (không cho phép nhảy cóc)
        $validTransitions = [
            'pending' => ['processed_and_ready_to_ship', 'canceled'],
            'processed_and_ready_to_ship' => ['dropped_off', 'canceled'],
            'dropped_off' => ['shipped'],
            'shipped' => ['out_for_delivery'],
            'out_for_delivery' => ['delivered'],
            'delivered' => ['received'],
        ];

        // Kiểm tra nếu trạng thái mới không hợp lệ dựa trên trạng thái hiện tại
        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return response()->json([
                'status' => 'error',
                'message' => "Invalid status transition from $currentStatus to $newStatus."
            ], 400);
        }

        // Nếu trạng thái mới là "canceled", bắt buộc nhập lý do
        if ($newStatus === 'canceled') {
            if (empty($reason)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please provide a reason for order cancellation.'
                ], 400);
            }

            // Không thể hủy nếu đơn hàng đã được vận chuyển hoặc giao thành công
            if (in_array($currentStatus, ['shipped', 'out_for_delivery', 'delivered'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot cancel an order that is already shipped or delivered.'
                ], 400);
            }

            // Nếu đơn hàng thanh toán qua thẻ/ví (không phải COD), cập nhật trạng thái thanh toán
            if ($order->payment_method != 'COD' && $order->payment_status == 1) {
                $order->payment_status = 0;
                // Gửi task hoàn tiền lên Trello
                $this->createTrelloRefundTask($order, $request->cancel_reason);
            }
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
            'message' => 'Order status updated successfully'
        ]);
    }

    public function changePaymentStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);

        // Nếu trạng thái thanh toán đã là "Completed" thì không cho phép cập nhật lại thành "Pending"
        if ($order->payment_status == 1 && $request->status == 0) {
            return response([
                'status' => 'error',
                'message' => 'Cannot revert payment status from Completed to Pending'
            ]);
        }
        // Nếu đơn hàng chưa được giao (Delivered) hoặc chưa có xác nhận (Received), không thể đặt Payment Status = Completed
        if ($request->status == 1 && !in_array($order->order_status, ['delivered', 'received'])) {
            return response([
                'status' => 'error',
                'message' => 'Cannot mark payment as Completed for COD orders before the order is delivered or received'
            ], 400);
        }

        // Cập nhật trạng thái nếu hợp lệ
        $order->payment_status = $request->status;
        $order->save();

        return response([
            'status' => 'success',
            'message' => 'Updated payment status'
        ]);
    }
}
