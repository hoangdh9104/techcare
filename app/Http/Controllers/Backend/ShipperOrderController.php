<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
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
    // dd($data->get());
        return $dataTable->render('shipper.orders.index',compact('data'));
    }
    public function pickUpOrder($orderId)
    {
        // Shipper nhận đơn hàng
        $orderShipper = OrderShipper::where('order_id', $orderId)->first();
        if ($orderShipper) {
            $orderShipper->status = 'in_delivery'; // Trạng thái shipper là đang giao hàng
            $orderShipper->save();

            return redirect()->route('shipper.orders')->with('success', 'Đơn hàng đã được nhận.');
        }

        return redirect()->route('shipper.orders')->with('error', 'Không tìm thấy đơn hàng.');
    }

    public function deliverOrder($orderId)
    {
        // Shipper giao hàng
        $orderShipper = OrderShipper::where('order_id', $orderId)->first();
        if ($orderShipper) {
            $orderShipper->status = 'delivered'; // Trạng thái shipper là đã giao hàng
            $orderShipper->delivered_at = now(); // Thời gian giao hàng
            $orderShipper->save();

            // Cập nhật trạng thái trong bảng orders
            $order = Order::find($orderId);
            if ($order) {
                $order->order_status = 'delivered'; // Trạng thái đơn hàng là đã giao
                $order->save();
            }

            return redirect()->route('shipper.orders')->with('success', 'Đơn hàng đã được giao thành công.');
        }

        return redirect()->route('shipper.orders')->with('error', 'Không tìm thấy đơn hàng.');
    }

}
