<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\FlashSaleItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FlashSaleController extends Controller
{
    public function index(FlashSaleItemDataTable $dataTable)
    {
        $flashSaleDate = FlashSale::first();
        $products = Product::where('is_approved', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        return $dataTable->render('admin.flash-sale.index', compact('flashSaleDate', 'products'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'end_date' => ['required'],
        ]);

        // Nếu không có dữ liệu sẽ tự tạo dữ liệu với ID = 1, nếu có sẽ cập nhật data mới
        FlashSale::updateOrCreate(
            ['id' => 1],
            ['end_date' => $request->end_date]
        );

        toastr('Cập nhật thành công!', 'success', 'Success');

        return redirect()->back();
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product' => ['required', 'unique:flash_sale_items,product_id'],
            'show_at_home' => ['required', Rule::in(0, 1)],
            'status' => ['required', Rule::in(0, 1)],
        ], [
            'product.required' => 'Vui lòng chọn một sản phẩm.',
            'product.unique' => 'Sản phẩm đã tồn tại trong chương trình khuyến mãi đặc biệt.',
            'show_at_home.required' => 'Vui lòng chọn trạng thái hiển thị trên trang chủ.',
            'status.required' => 'Vui lòng chọn trạng thái của sản phẩm.',
        ]);

        $flashSaleDate = FlashSale::first();
        $flashSaleItem = new FlashSaleItem();
        $flashSaleItem->product_id = $request->product;
        $flashSaleItem->flash_sale_id = $flashSaleDate->id;
        $flashSaleItem->show_at_home = $request->show_at_home;
        $flashSaleItem->status = $request->status;
        $flashSaleItem->save();

        toastr('Đã thêm sản phẩm', 'success', 'Success');

        return redirect()->back();
    }

    public function changeShowAtHomeStatus(Request $request)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($request->id);
        $flashSaleItem->show_at_home = $request->status == true ? 1 : 0;
        $flashSaleItem->save();

        return response(['message' => 'Đã thay đổi trang thái!']);
    }

    public function changeStatus(Request $request)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($request->id);
        $flashSaleItem->status = $request->status == true ? 1 : 0;
        $flashSaleItem->save();

        return response(['message' => 'Đã thay đổi trang thái!']);
    }

    public function destroy(string $id)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($id);
        $flashSaleItem->delete();

        return response(['status' => 'success', 'message' => 'Xóa thành công!']);
    }
}
