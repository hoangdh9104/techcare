<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CustomerListDataTable;
use App\Http\Controllers\Controller;
use App\Models\CustomerStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerListDataTable $dataTable)
    {
        return $dataTable->render('admin.customer-list.index');
    }

    public function statusChange(Request $request)
    {
        $customer = User::findOrFail($request->id);
        $reason = $request->reason ?? '';
        $customer->status = $request->status == 'active' ? 'active' : 'inactive';
        $customer->save();

        CustomerStatusHistory::create([
            'user_id' => $customer->id,
            'status' => $customer->status,
            'reason' => $reason,
            'updated_by' => auth()->user()->id,
            'changed_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Trạng thái khách hàng được cập nhật thành công'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $customer = User::findOrFail($id);
        return view('admin.customer-list.show', compact('customer'));
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
        //
    }
}
