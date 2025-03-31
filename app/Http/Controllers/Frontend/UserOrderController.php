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
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng không tồn tại hoặc bạn không có quyền hủy'], 403);
        }

        if (!in_array($order->order_status, ['pending', 'processed_and_ready_to_ship'])) {
            return response()->json(['status' => 'error', 'message' => 'Không thể hủy đơn hàng này'], 400);
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
            'reason' => 'Người dùng hủy đơn hàng. Lý do: ' . $request->reason
        ]);

        return response()->json(['status' => 'success', 'message' => 'Đơn hàng đã được hủy và lưu vào lịch sử thành công!']);
    }
}
