<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderShipperDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderShipper;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShipperOrderController extends Controller
{
    public function index(OrderShipperDataTable $dataTable)
    {
        return $dataTable->render('shipper.orders.index');
    }

    // public function pickUpOrder($orderId)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::findOrFail($orderId);

    //         // Validate order status
    //         if ($order->order_status !== 'pending') {
    //             return redirect()->back()->with('error', 'Đơn hàng không còn trong trạng thái có thể nhận.');
    //         }

    //         // Update order shipper status
    //         $orderShipper = OrderShipper::updateOrCreate(
    //             ['order_id' => $orderId],
    //             [
    //                 'status' => 'in_delivery',
    //                 'shipper_id' => Auth::id()
    //             ]
    //         );

    //         // Update main order status
    //         $order->update(['order_status' => 'shipped']);

    //         // Create status history
    //         OrderStatusHistory::create([
    //             'order_id' => $orderId,
    //             'status' => 'shipped',
    //             'updated_by' => Auth::id(),
    //             'shipper_id' => Auth::id(),
    //             'changed_at' => now(),
    //             'reason' => 'Shipper nhận đơn để giao'
    //         ]);

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Đã nhận đơn hàng để giao.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
    //     }
    // }


    public function pickUpOrder($orderId)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($orderId);

            if ($order->order_status !== 'pending') {
                return redirect()->back()->with('error', 'Đơn hàng không thể nhận');
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['order_status' => 'shipped']);

            // Ghi lịch sử
            OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => 'shipped',
                'updated_by' => Auth::id(),
                'changed_at' => now(),
                'reason' => 'Shipper nhận đơn để giao'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã nhận đơn hàng');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function deliverOrder($orderId)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($orderId);

            // Validate order status
            if ($order->order_status !== 'shipped') {
                return redirect()->back()->with('error', 'Đơn hàng chưa được nhận để giao.');
            }

            // Update order shipper status
            $orderShipper = OrderShipper::where('order_id', $orderId)->firstOrFail();
            $orderShipper->update([
                'status' => 'delivered',
                'delivered_at' => now()
            ]);

            // Update main order status
            $order->update(['order_status' => 'delivered']);

            // Create status history
            OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => 'delivered',
                'updated_by' => Auth::id(),
                'shipper_id' => Auth::id(),
                'changed_at' => now(),
                'reason' => 'Shipper đã giao hàng thành công'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã xác nhận giao hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($orderId)
    {
        $order = Order::with(['orderProducts.product', 'user', 'statusHistories.user', 'statusHistories.shipper'])
            ->findOrFail($orderId);

        $addressData = json_decode($order->order_address, true);
        $shippingMethod = json_decode($order->shpping_method, true);
        $coupon = json_decode($order->coupon, true);

        $orderShipper = OrderShipper::where('order_id', $orderId)->first();

        return view('shipper.orders.show', compact(
            'order',
            'orderShipper',
            'addressData',
            'shippingMethod',
            'coupon'
        ));
    }

    // public function cancelOrder(Request $request, $orderId)
    // {
    //     $request->validate(['cancel_reason' => 'required|string|max:255']);

    //     DB::beginTransaction();
    //     try {
    //         $order = Order::findOrFail($orderId);

    //         // Validate order status
    //         if (!in_array($order->order_status, ['pending', 'processed'])) {
    //             return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng chưa được giao.');
    //         }

    //         // Update main order status
    //         $order->update([
    //             'order_status' => 'cancelled',
    //             'cancel_reason' => $request->cancel_reason
    //         ]);

    //         // Update order shipper status if exists
    //         if ($orderShipper = OrderShipper::where('order_id', $orderId)->first()) {
    //             $orderShipper->update(['status' => 'cancelled']);
    //         }

    //         // Create status history
    //         OrderStatusHistory::create([
    //             'order_id' => $orderId,
    //             'status' => 'cancelled',
    //             'updated_by' => Auth::id(),
    //             'shipper_id' => Auth::id(),
    //             'changed_at' => now(),
    //             'reason' => $request->cancel_reason
    //         ]);

    //         DB::commit();
    //         return redirect()->route('shipper.orders.index')->with('success', 'Đã hủy đơn hàng thành công.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    //     }
    // }
    public function cancelOrder(Request $request, $orderId)
    {
        $request->validate(['cancel_reason' => 'required|string|max:255']);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($orderId);

            $order->update([
                'order_status' => 'cancelled',
                'cancel_reason' => $request->cancel_reason
            ]);

            OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => 'cancelled',
                'updated_by' => Auth::id(),
                'changed_at' => now(),
                'reason' => $request->cancel_reason
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã hủy đơn hàng');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
