<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CanceledOrderDataTable;
use App\DataTables\DeliveredOrderDataTable;
use App\DataTables\DroppedOffOrderDataTable;
use App\DataTables\OrderDataTable;
use App\DataTables\OutForDeliveryOffOrderDataTable;
use App\DataTables\PendingOrderDataTable;
use App\DataTables\ProcessedOrderDataTable;
use App\DataTables\ShippedOrderDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
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
        $order = Order::findOrFail($id);
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

        // Danh sách trạng thái không thể quay lại từ trạng thái đã giao hàng
        $immutableStatuses = ['delivered', 'canceled'];

        // Nếu đơn hàng đã ở trạng thái không thể thay đổi thì từ chối cập nhật
        if (in_array($currentStatus, $immutableStatuses)) {
            $messages = [
                'delivered' => 'Order has already been delivered and cannot be changed.',
                'canceled'  => 'Order has already been canceled and cannot be changed.'
            ];

            return response([
                'status'  => 'error',
                'message' => $messages[$currentStatus] ?? 'Order status cannot be changed.'
            ], 400);
        }

        // Nếu đơn hàng đã được 'shipped', không thể chuyển về các trạng thái trước đó
        if ($currentStatus === 'shipped' && in_array($newStatus, ['pending', 'processed_and_ready_to_ship', 'dropped_off', 'canceled'])) {
            return response([
                'status' => 'error',
                'message' => 'Cannot change order status from shipped to ' . ucfirst(str_replace('_', ' ', $newStatus))
            ], 400);
        }

        // Nếu đơn hàng đã 'out_for_delivery', không thể quay lại trạng thái trước đó
        if ($currentStatus === 'out_for_delivery' && in_array($newStatus, ['pending', 'processed_and_ready_to_ship', 'dropped_off', 'shipped', 'canceled'])) {
            return response([
                'status' => 'error',
                'message' => 'Cannot change order status from out for delivery to ' . ucfirst(str_replace('_', ' ', $newStatus))
            ], 400);
        }

        // Cập nhật trạng thái đơn hàng
        $order->order_status = $newStatus;
        $order->save();

        return response([
            'status' => 'success',
            'message' => 'Order status updated successfully'
        ]);
    }
    public function changePaymentStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->payment_status = $request->status;
        $order->save();
        return response(['status' => 'success', 'message' => 'updated payment status']);
    }
}
