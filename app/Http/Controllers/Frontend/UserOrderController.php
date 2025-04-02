<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
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
        $order = Order::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'The order does not exist or you do not have the right to cancel it'], 403);
        }

        if (!in_array($order->order_status, ['pending', 'processed_and_ready_to_ship'])) {
            return response()->json(['status' => 'error', 'message' => 'This order cannot be cancelled'], 400);
        }
        // Kiểm tra nếu đơn hàng đã thanh toán qua thẻ ví (payment_status = 1)
        if ($order->payment_status === 1) {
            $order->payment_status = 0; // Đánh dấu thanh toán chưa thực hiện (vì đang hủy đơn)
            $order->save();
        }
        // Cập nhật trạng thái đơn hàng
        $order->update([
            'order_status' => 'canceled',
            'cancel_reason' => $request->reason
        ]);

        // Lưu vào lịch sử trạng thái đơn hàng
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'canceled',
            'updated_by' => auth()->id(),
            'reason' => 'Reason: ' . $request->reason
        ]);

        return response()->json(['status' => 'success', 'message' => 'Order has been canceled and saved to history successfully!']);
    }
}
