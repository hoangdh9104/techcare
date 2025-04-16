<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderShipperDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderShipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipperOrderController extends Controller
{
    public function index(OrderShipperDataTable $dataTable)
    {
        $data = DB::table('order_shippers')
            ->join('orders', 'orders.id', '=', 'order_shippers.order_id')
            ->select('order_shippers.id', 'orders.order_code', 'order_shippers.status', 'order_shippers.delivered_at', 'order_shippers.created_at', 'order_shippers.updated_at')
            ->orderBy('order_shippers.id', 'desc')
            ->limit(10)
            ->get();

        return $dataTable->render('shipper.orders.index', compact('data'));
    }

    public function pickUpOrder($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Chỉ cho phép nhận đơn nếu trạng thái đơn là "dropped_off"
        if ($order->order_status !== 'dropped_off') {
            return redirect()->back()->with('error', 'Đơn hàng không còn trong trạng thái có thể nhận.');
        }

        $orderShipper = OrderShipper::where('order_id', $orderId)->first();
        if ($orderShipper) {
            $orderShipper->status = 'in_delivery';
            $orderShipper->save();

            // Cập nhật trạng thái đơn hàng thành "shipped"
            $order->order_status = 'shipped';
            $order->save();

            return redirect()->back()->with('success', 'Đơn hàng đã được nhận để giao.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy thông tin giao hàng.');
    }

    public function deliverOrder($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Chỉ cho phép giao nếu đơn đã ở trạng thái "shipped"
        if ($order->order_status !== 'shipped') {
            return redirect()->back()->with('error', 'Không thể giao đơn hàng chưa được nhận.');
        }

        $orderShipper = OrderShipper::where('order_id', $orderId)->first();
        if ($orderShipper) {
            $orderShipper->status = 'delivered';
            $orderShipper->delivered_at = now();
            $orderShipper->save();

            $order->order_status = 'delivered';
            $order->save();

            return redirect()->back()->with('success', 'Đơn hàng đã được giao thành công.');
        }

        return redirect()->back()->with('error', 'Không tìm thấy thông tin giao hàng.');
    }
    public function show($orderId)
    {
        // Lấy đơn hàng với mối quan hệ 'products' và 'user'
        $order = Order::with(['products', 'user'])
            ->where('id', $orderId)  // Tìm theo ID đơn hàng
            ->firstOrFail();  // Trả về lỗi 404 nếu không tìm thấy đơn hàng
            $addressData = json_decode($order->order_address, true);
        // Lấy thông tin giao hàng của đơn hàng
        $orderShipper = OrderShipper::where('order_id', $orderId)
            ->first();  // Trả về thông tin giao hàng đầu tiên nếu có

        // Trả về view với các biến cần thiết
        return view('shipper.orders.show', compact('order', 'orderShipper','addressData'));
    }

}
