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
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:200',
            'type' => 'required|string',
            'min_cost' => ['nullable', 'integer', 'min:0', function ($attribute, $value, $fail) use ($request) {
                if ($request->type === 'min_cost' && is_null($value)) {
                    $fail('The min cost field is required when type is min_cost.');
                }
            }],
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ]);
        $shipping = new ShippingRule();
        $shipping->name = $request->name;
        $shipping->type = $request->type;
        $shipping->min_cost = $request->min_cost;
        $shipping->cost = $request->cost;
        $shipping->status = $request->status;
        $shipping->save();
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
        $request->validate([
            'name' => 'required|string|max:200',
            'type' => 'required|string',
            'min_cost' => ['nullable', 'integer', 'min:0', function ($attribute, $value, $fail) use ($request) {
                if ($request->type === 'min_cost' && is_null($value)) {
                    $fail('The min cost field is required when type is min_cost.');
                }
            }],
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ]);
        $shipping = ShippingRule::findOrFail($id);
        $shipping->name = $request->name;
        $shipping->type = $request->type;
        $shipping->min_cost = $request->min_cost;
        $shipping->cost = $request->cost;
        $shipping->status = $request->status;
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

        return redirect()->route('admin.shipping-rule.index')
            ->with('success', 'Deleted Successfully');
    }
    public function changeStatus(Request $request)
    {
        $shipping = ShippingRule::findOrFail($request->id);
        $shipping->status = $request->status;
        $shipping->save();

        return response(['message' => 'Status has been updated!']);
    }
}
