<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ShippingRuleDataTable;
use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use Illuminate\Http\Request;

class ShippingRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShippingRuleDataTable $dataTable)
    {
        return $dataTable->render('admin.shipping-rule.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shipping-rule.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tạo rules ban đầu
        $rules = [
            'name' => 'required|string|max:200',
            'type' => 'required|string|in:flat_cost,min_cost',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'min_cost' => 'nullable|numeric|min:0',
        ];

        // Nếu type = min_cost thì min_cost bắt buộc
        if ($request->input('type') === 'min_cost') {
            $rules['min_cost'] = 'required|numeric|min:0';
        }

        // Validate
        $validatedData = $request->validate($rules);

        // Tạo mới dữ liệu
        $shipping = new ShippingRule();
        $shipping->name = $validatedData['name'];
        $shipping->type = $validatedData['type'];
        $shipping->min_cost = $validatedData['min_cost'] ?? null;  // dùng ?? null cho chắc
        $shipping->cost = $validatedData['cost'];
        $shipping->status = $validatedData['status'];
        $shipping->save();


        // Thông báo & điều hướng
        toastr('Created Successfully!', 'success', 'Success');
        return redirect()->route('admin.shipping-rule.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shipping = ShippingRule::findOrFail($id);
        return view('admin.shipping-rule.edit', compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|max:200',
            'type' => 'required|string|in:flat_cost,min_cost',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'min_cost' => 'nullable|numeric|min:0',
        ];

        // Nếu type = min_cost thì min_cost bắt buộc
        if ($request->input('type') === 'min_cost') {
            $rules['min_cost'] = 'required|numeric|min:0';
        }

        // Validate
        $validatedData = $request->validate($rules);
        $shipping = ShippingRule::findOrFail($id);
        $shipping->name = $validatedData['name'];
        $shipping->type = $validatedData['type'];
        $shipping->min_cost = $validatedData['min_cost'] ?? null;  // dùng ?? null cho chắc
        $shipping->cost = $validatedData['cost'];
        $shipping->status = $validatedData['status'];
        $shipping->save();
        toastr('Update Successfully!', 'success', 'Success');
        return redirect()->route('admin.shipping-rule.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shipping = ShippingRule::findOrFail($id);
        $shipping->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        $shipping = ShippingRule::findOrFail($request->id);
        $shipping->status = $request->status;
        $shipping->save();

        return response(['message' => 'Status has been updated!']);
    }
}
