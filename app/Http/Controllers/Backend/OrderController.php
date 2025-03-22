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
        $order = Order::findOrFail($request->id);
        $order->order_status = $request->status;
        $order->save();
        return response(['status' => 'success', 'message' => 'updated order status']);
    }
    public function changePaymentStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->payment_status = $request->status;
        $order->save();
        return response(['status' => 'success', 'message' => 'updated payment status']);
    }
}
