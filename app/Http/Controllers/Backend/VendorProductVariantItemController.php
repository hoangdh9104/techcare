<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorProductVariantItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

class VendorProductVariantItemController extends Controller
{
    public function index(VendorProductVariantItemDataTable $dataTable, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);
        return $dataTable->render('vendor.product.product-variant-item.index', compact('product', 'variant'));
    }
    public function create(string $productId, string $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $product = Product::findOrFail($productId);
        return view('vendor.product.product-variant-item.create', compact('variant', 'product'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => ['integer', 'required'],
            'name' => ['required', 'max:200'],
            'price' => ['integer', 'required'],
            'is_default' => ['required'],
            'status' => ['required']
        ]);
        $variantItem = new ProductVariantItem();
        $variantItem->product_variant_id = $request->variant_id;
        $variantItem->name = $request->name;
        $variantItem->price = $request->price;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();

        toastr('Đã tạo thành công!', 'Thành công', 'Thành công');
        return redirect()->route(
            'vendor.products-variant-item.index',
            ['productId' => $request->product_id, 'variantId' => $request->variant_id]
        );
    }
    public function edit(string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        return view('vendor.product.product-variant-item.edit', compact('variantItem'));
    }
    public function update(Request $request, string $variantItemId)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'price' => ['integer', 'required'],
            'is_default' => ['required'],
            'status' => ['required']
        ]);

        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        $variantItem->name = $request->name;
        $variantItem->price = $request->price;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();

        toastr('Update Successfully!', 'success', 'success');

        return redirect()->route('vendor.products-variant-item.index', [
            'productId' => $variantItem->productVariant->product_id,
            'variantId' => $variantItem->product_variant_id
        ]);


    }
    public function destroy(string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);

        // Kiểm tra xem variantItem có liên quan đến bất kỳ dữ liệu nào khác không
        $relatedCheck = ProductVariantItem::where('product_variant_id', $variantItem->product_variant_id)->count();

        if ($relatedCheck <= 1) {
            return response([
                'status' => 'error',
                'message' => 'Cannot delete the last variant item. At least one variant item is required!'
            ]);
        }

        // Xóa nếu điều kiện được thỏa mãn
        $variantItem->delete();

        return response(['status' => 'Thành công', 'Tin nhắn' => 'Đã xóa thành công']);
    }

    public function changeStatus(Request $request)
    {
        $variantItem = ProductVariantItem::findOrFail($request->id);
        $variantItem->status = $request->status == 'true' ? 1 : 0;
        $variantItem->save();

        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }

}
