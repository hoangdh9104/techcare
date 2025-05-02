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
        $messages = [
            'name.required' => 'Vui lòng nhập tên quy tắc.',
            'name.string' => 'Tên quy tắc phải là chuỗi.',
            'name.max' => 'Tên quy tắc không được vượt quá 200 ký tự.',
            'type.required' => 'Vui lòng chọn loại quy tắc.',
            'type.in' => 'Loại quy tắc không hợp lệ.',
            'cost.required' => 'Vui lòng nhập phí vận chuyển.',
            'cost.numeric' => 'Phí vận chuyển phải là số.',
            'cost.min' => 'Phí vận chuyển không được nhỏ hơn 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'min_cost.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_cost.min' => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',
        ];
        // Nếu type = min_cost thì min_cost bắt buộc
        if ($request->input('type') === 'min_cost') {
            $rules['min_cost'] = 'required|numeric|min:0';
        }

        // Validate
        $validatedData = $request->validate($rules, $messages);

        // Tạo mới dữ liệu
        $shipping = new ShippingRule();
        $shipping->name = $validatedData['name'];
        $shipping->type = $validatedData['type'];
        $shipping->min_cost = $validatedData['min_cost'] ?? null;  // dùng ?? null cho chắc
        $shipping->cost = $validatedData['cost'];
        $shipping->status = $validatedData['status'];
        $shipping->save();


        // Thông báo & điều hướng
        toastr('Tạo mới thành công!', 'success', 'Success');
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
        $messages = [
            'name.required' => 'Vui lòng nhập tên quy tắc.',
            'name.string' => 'Tên quy tắc phải là chuỗi.',
            'name.max' => 'Tên quy tắc không được vượt quá 200 ký tự.',
            'type.required' => 'Vui lòng chọn loại quy tắc.',
            'type.in' => 'Loại quy tắc không hợp lệ.',
            'cost.required' => 'Vui lòng nhập phí vận chuyển.',
            'cost.numeric' => 'Phí vận chuyển phải là số.',
            'cost.min' => 'Phí vận chuyển không được nhỏ hơn 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'min_cost.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_cost.min' => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',
        ];
        // Nếu type = min_cost thì min_cost bắt buộc
        if ($request->input('type') === 'min_cost') {
            $rules['min_cost'] = 'required|numeric|min:0';
        }

        // Validate
        $validatedData = $request->validate($rules, $messages);
        $shipping = ShippingRule::findOrFail($id);
        $shipping->name = $validatedData['name'];
        $shipping->type = $validatedData['type'];
        $shipping->min_cost = $validatedData['min_cost'] ?? null;  // dùng ?? null cho chắc
        $shipping->cost = $validatedData['cost'];
        $shipping->status = $validatedData['status'];
        $shipping->save();
        toastr('Cập nhật thành công!', 'success', 'Success');
        return redirect()->route('admin.shipping-rule.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shipping = ShippingRule::findOrFail($id);
        $shipping->delete();

        return response(['status' => 'success', 'message' => 'Xóa thành công !']);
    }
    public function changeStatus(Request $request)
    {
        $shipping = ShippingRule::findOrFail($request->id);
        $shipping->status = $request->status;
        $shipping->save();

        return response(['message' => 'Cập nhật trang thái!']);
    }
}
