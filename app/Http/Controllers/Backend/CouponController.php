<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CouponDataTable;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CouponDataTable $dataTable)
    {
        return $dataTable->render('admin.coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.coupon.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'code' => ['required', 'max:200'],
            'quantity' => ['required', 'integer'],
            'max_use' => ['required', 'integer'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'discount_type' => ['required', 'max:200'],
            'discount' => ['required', 'integer'],
            'status' => ['required', 'integer']

        ]);
        // Kiểm tra nếu ngày kết thúc đã quá hạn
        if (strtotime($request->end_date) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->withErrors(['end_date' => 'Ngày kết thúc không được là ngày trong quá khứ.']);
        }

        // Kiểm tra giá trị giảm giá
        if ($request->discount_type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withErrors(['discount' => 'Tỷ lệ chiết khấu không được vượt quá 100%.']);
        }

        if ($request->discount_type === 'amount' && $request->discount > 1000) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền chiết khấu không được vượt quá 1000.']);
        }

        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->quantity = $request->quantity;
        $coupon->max_use = $request->max_use;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount = $request->discount;
        $coupon->total_used = 0;
        $coupon->status = $request->status;
        $coupon->product_id = $request->product_id;
        $coupon->save();

        toastr('Đã tạo thành công', 'thành công', 'Thành công');

        return redirect()->route('admin.coupons.index');
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
        $coupon = Coupon::findOrFail($id);
        $products = Product::all();
        return view('admin.coupon.edit', compact('coupon','products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'code' => ['required', 'max:200'],
            'quantity' => ['required', 'integer'],
            'max_use' => ['required', 'integer'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'discount_type' => ['required', 'max:200'],
            'discount' => ['required', 'integer'],
            'status' => ['required', 'integer']

        ]);
         // Kiểm tra nếu ngày kết thúc đã quá hạn
        if (strtotime($request->end_date) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->withErrors(['end_date' => 'Ngày kết thúc không được là ngày trong quá khứ.']);
        }
        // Kiểm tra giá trị giảm giá
        if ($request->discount_type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withErrors(['discount' => 'Tỷ lệ chiết khấu không được vượt quá 100%.']);
        }

        if ($request->discount_type === 'amount' && $request->discount > 1000) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền chiết khấu không được vượt quá 1000.']);
        }

        $coupon = Coupon::findOrFail($id);
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->quantity = $request->quantity;
        $coupon->max_use = $request->max_use;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount = $request->discount;
        $coupon->status = $request->status;
        $coupon->product_id = $request->product_id;
        $coupon->save();

        toastr('Đã tạo thành công.', 'thành công.', 'Thành công.');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response(['status' => 'thành công.', 'Tin nhắn' => 'Đã xóa thành công!']);
    }

    public function changeStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status == 'true' ? 1 : 0;
        $coupon->save();

        return response(['Tin nhắn' => 'Trạng thái đã được cập nhật!']);
    }
}
