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
            'name' => ['required', 'string', 'max:200', 'unique:coupons,name', 'regex:/^[a-zA-Z0-9\s]+$/'], // Tên không trùng, chỉ chứa chữ/số/khoảng trắng
            'code' => ['required', 'string', 'max:20', 'unique:coupons,code', 'regex:/^[A-Z0-9-]+$/'], // Mã coupon ngắn gọn, không trùng, chỉ chứa chữ in hoa/số/dấu gạch ngang
            'quantity' => ['required', 'integer', 'min:1'], // Số lượng coupon phải lớn hơn 0
            'max_use' => ['required', 'integer', 'min:1'], // Số lần sử dụng tối đa phải lớn hơn 0
            'start_date' => ['required', 'date', 'after_or_equal:today'], // Ngày bắt đầu phải từ hôm nay trở đi
            'end_date' => ['required', 'date', 'after:start_date'], // Ngày kết thúc phải sau ngày bắt đầu
            'discount_type' => ['required', 'in:percent,amount'], // Chỉ cho phép 2 loại giảm giá
            'discount' => ['required', 'numeric', 'min:0'], // Giá trị giảm giá không âm
            'status' => ['required', 'integer', 'in:0,1'], // Trạng thái chỉ là 0 (tắt) hoặc 1 (bật)
            'product_id' => ['nullable', 'integer', 'exists:products,id'], // product_id nếu có phải tồn tại trong bảng products
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'name.unique' => 'Tên đã tồn tại.',
            'name.regex' => 'Tên chỉ được chứa chữ, số và khoảng trắng.',
            'code.required' => 'Mã là bắt buộc.',
            'code.unique' => 'Mã đã tồn tại.',
            'code.regex' => 'Mã chỉ được chứa chữ in hoa, số và dấu gạch ngang.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'max_use.required' => 'Số lần sử dụng tối đa là bắt buộc.',
            'max_use.min' => 'Số lần sử dụng tối đa phải lớn hơn 0.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount.min' => 'Giá trị giảm giá không được âm.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
        ]);

        // Kiểm tra logic bổ sung
        if ($request->quantity < $request->max_use) {
            return redirect()->back()->withErrors(['max_use' => 'Số lần sử dụng tối đa không được vượt quá số lượng coupon.']);
        }
        // Kiểm tra nếu ngày kết thúc đã quá hạn
        if (strtotime($request->end_date) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->withErrors(['end_date' => 'Ngày kết thúc không được ở trong quá khứ.']);
        }

        // Kiểm tra giá trị giảm giá
        if ($request->discount_type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withErrors(['discount' => 'Phần trăm giảm giá không được vượt quá 100%.']);
        }

        if ($request->discount_type === 'amount' && $request->discount > 1000) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền giảm giá không được vượt quá 1000.']);
        }
        if ($request->discount_type === 'amount' && $request->discount < 1) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền giảm giá phải lớn hơn 0.']);
        }
        // Kiểm tra nếu coupon áp dụng cho sản phẩm cụ thể
        if ($request->product_id && $request->discount_type === 'percent') {
            $product = \App\Models\Product::find($request->product_id);
            if ($product && ($request->discount * $product->price / 100) > $product->price) {
                return redirect()->back()->withErrors(['discount' => 'Phần trăm giảm giá khiến giá sản phẩm nhỏ hơn 0.']);
            }
        }
        // Kiểm tra mã coupon không được trùng với các từ khóa nhạy cảm (nếu cần)
        $reservedCodes = ['ADMIN', 'SUPER', 'TEST'];
        if (in_array(strtoupper($request->code), $reservedCodes)) {
            return redirect()->back()->withErrors(['code' => 'Mã coupon này không được phép sử dụng.']);
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

        toastr('Đã tạo thành công', 'success', 'Thành công');

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

        ], [
            'name.required' => 'Tên là bắt buộc.',
            'name.unique' => 'Tên đã tồn tại.',
            'name.regex' => 'Tên chỉ được chứa chữ, số và khoảng trắng.',
            'code.required' => 'Mã là bắt buộc.',
            'code.unique' => 'Mã đã tồn tại.',
            'code.regex' => 'Mã chỉ được chứa chữ in hoa, số và dấu gạch ngang.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'max_use.required' => 'Số lần sử dụng tối đa là bắt buộc.',
            'max_use.min' => 'Số lần sử dụng tối đa phải lớn hơn 0.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount.min' => 'Giá trị giảm giá không được âm.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
        ]);

         // Kiểm tra logic bổ sung
         if ($request->quantity < $request->max_use) {
            return redirect()->back()->withErrors(['max_use' => 'Số lần sử dụng tối đa không được vượt quá số lượng coupon.']);
        }
        // Kiểm tra nếu ngày kết thúc đã quá hạn
        if (strtotime($request->end_date) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->withErrors(['end_date' => 'Ngày kết thúc không được ở trong quá khứ.']);
        }

        // Kiểm tra giá trị giảm giá
        if ($request->discount_type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withErrors(['discount' => 'Phần trăm giảm giá không được vượt quá 100%.']);
        }

        if ($request->discount_type === 'amount' && $request->discount > 1000) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền giảm giá không được vượt quá 1000.']);
        }
        if ($request->discount_type === 'amount' && $request->discount < 1) {
            return redirect()->back()->withErrors(['discount' => 'Số tiền giảm giá phải lớn hơn 0.']);
        }
        // Kiểm tra nếu coupon áp dụng cho sản phẩm cụ thể
        if ($request->product_id && $request->discount_type === 'percent') {
            $product = \App\Models\Product::find($request->product_id);
            if ($product && ($request->discount * $product->price / 100) > $product->price) {
                return redirect()->back()->withErrors(['discount' => 'Phần trăm giảm giá khiến giá sản phẩm nhỏ hơn 0.']);
            }
        }
        // Kiểm tra mã coupon không được trùng với các từ khóa nhạy cảm (nếu cần)
        $reservedCodes = ['ADMIN', 'SUPER', 'TEST'];
        if (in_array(strtoupper($request->code), $reservedCodes)) {
            return redirect()->back()->withErrors(['code' => 'Mã coupon này không được phép sử dụng.']);
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

        toastr('Cập nhật thành công', 'success', 'Thành công');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response(['status' => 'success', 'message' => 'Xóa thành công!']);
    }

    public function changeStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status == 'true' ? 1 : 0;
        $coupon->save();

        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
}
