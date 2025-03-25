<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function index(VendorOrderDataTable $dataTable)
    {
        return $dataTable->render('vendor.order.index');
    }
    public function show(string $id)
    {
        $order = Order::with(['orderProducts'])->findOrFail($id);
        return view('vendor.order.show', compact('order'));
    }
    public function orderStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $currentStatus = $order->order_status;
        $newStatus = $request->status;

        // Danh sách trạng thái vendor được phép cập nhật
        $allowedStatuses = ['pending', 'processed_and_ready_to_ship'];

        // Kiểm tra nếu đơn hàng đã được shipped trở đi thì không cho phép vendor cập nhật
        if (!in_array($currentStatus, $allowedStatuses)) {
            return redirect()->back()->with('error', 'You are not allowed to update the order status after it has been shipped.');
        }

        // Kiểm tra nếu vendor đang cố cập nhật sang trạng thái không hợp lệ
        if (!in_array($newStatus, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Invalid status update. Vendors can only change status to pending or processed_and_ready_to_ship.');
        }

        // Cập nhật trạng thái đơn hàng
        $order->order_status = $newStatus;
        $order->save();

        toastr('Status Updated Successfully!', 'success', 'Success');

        return redirect()->back();
    }
}
