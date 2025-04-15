<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\TrelloTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceivedMail;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    use TrelloTrait;
    public function index(UserOrderDataTable $dataTable)
    {
        return $dataTable->render('frontend.dashboard.order.index');
    }

    public function show(string $id)
    {
        $order = Order::with(['orderProducts.product', 'statusHistories' => function ($query) {
            $query->orderBy('changed_at', 'desc');
        }])->findOrFail($id);
        return view('frontend.dashboard.order.show', compact('order'));
    }
    public function cancel(Request $request, $id)
    {
        // $order = Order::where('id', $id)->where('user_id', Auth::id())->first();
        $order = Order::with('orderProducts')->where('id', $id)->where('user_id', Auth::id())->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng không tồn tại hoặc bạn không có quyền hủy đơn hàng'], 403);
        }

        if (!in_array($order->order_status, ['pending', 'processed_and_ready_to_ship'])) {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng này không thể hủy'], 400);
        }
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
        // Kiểm tra nếu đơn hàng đã thanh toán qua thẻ ví (payment_status = 1)
        if ($order->payment_status === 1) {
            $order->payment_status = 0; // Đánh dấu thanh toán chưa thực hiện (vì đang hủy đơn)
            $order->save();
            // Gửi task hoàn tiền lên Trello
            $this->createTrelloRefundTask($order, $request->reason);
        }
        // Cập nhật trạng thái đơn hàng
        $order->update([
            'order_status' => 'canceled',
        ]);

        // Lưu vào lịch sử trạng thái đơn hàng
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'canceled',
            'updated_by' => auth()->id(),
            'reason' => $request->reason,
            'changed_at' => now()
        ]);
        return response()->json(['status' => 'success', 'message' => 'Đơn hàng đã được hủy và lưu vào lịch sử thành công!']);
    }
    public function markAsReceived($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->order_status !== 'delivered') {
            return response()->json([
                'status' => 'error',
                'message' => 'Chỉ những đơn hàng đã xác nhận mới có thể được giao.'
            ]);
        }

        $order->update([
            'order_status' => 'received',
            'updated_at' => now(),
        ]);
        // Lưu vào lịch sử trạng thái đơn hàng
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'received',
            'updated_by' => auth()->id(),
            'reason' => 'Đơn hàng đã được xác nhận bởi' . auth()->user()->name,
            'changed_at' => now()
        ]);
        // Gửi email xác nhận
        Mail::to(auth()->user()->email)->send(new OrderReceivedMail($order));

        return response()->json([
            'status' => 'success',
            'message' => 'Cảm ơn bạn đã xác nhận. Đơn hàng đã được đánh dấu là hoàn tất.'
        ]);
    }
}
