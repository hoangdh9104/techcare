<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantItemController extends Controller
{
    public function index(ProductVariantItemDataTable $dataTable, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);
        return $dataTable->render('admin.product.product-variant-item.index', compact('product', 'variant'));
    }
    public function create(string $productId, string $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $product = Product::findOrFail($productId);
        return view('admin.product.product-variant-item.create', compact('variant', 'product'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => ['integer', 'required'],
            // 'name' => ['required', 'max:200', Rule::unique('product_variant_items', 'name')],
            'name' => [
                'required',
                'max:200',
                Rule::unique('product_variant_items', 'name')
                    ->where(function ($query) use ($request) {
                        return $query->where('product_variant_id', $request->variant_id);
                    }),
            ],
            'is_default' => ['required'],
            'status' => ['required'],
        ]);
        // Kiểm tra nếu đã có biến thể mặc định
        if ($request->is_default == 1) {
            $existsDefault = ProductVariantItem::where('product_variant_id', $request->variant_id)
                ->where('is_default', 1)
                ->exists();

            if ($existsDefault) {
                return redirect()->back()->withErrors([
                    'is_default' => 'Đã tồn tại một biến thể mặc định cho nhóm biến thể này.',
                ])->withInput();
            }
        }
        $variantItem = new ProductVariantItem();
        $variantItem->product_variant_id = $request->variant_id;
        $variantItem->name = $request->name;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();
        toastr('Tạo mới chi tiết biến thể thành công!', 'success', 'success');
        return redirect()->route(
            'admin.products-variant-item.index',
            ['productId' => $request->product_id, 'variantId' => $request->variant_id]
        );
    }
    public function edit(string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        return view('admin.product.product-variant-item.edit', compact('variantItem'));
    }
    public function update(Request $request, string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);

        $request->validate([
            'name' => ['required', 'max:200',  Rule::unique('product_variant_items', 'name')
                ->where(function ($query) use ($request) {
                    return $query->where('product_variant_id', $request->variant_id);
                })->ignore($variantItemId)],
            'is_default' => ['required'],
            'status' => ['required']
        ]);

        // Kiểm tra nếu có ProductVariantItem khác đang is_default == 1
        if ($request->is_default == 1) {
            $existsDefault = ProductVariantItem::where('product_variant_id', $variantItem->product_variant_id)
                ->where('is_default', 1)
                ->where('id', '!=', $variantItemId) // Bỏ qua chính nó khi update
                ->exists();

            if ($existsDefault) {
                return redirect()->back()->withErrors(['is_default' => 'Sản phẩm đã có một biến thể mặc định.']);
            }
        }

        // Cập nhật dữ liệu
        $variantItem->name = $request->name;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();

        toastr('Cập nhật biến thể thành công!', 'success', 'success');

        return redirect()->route(
            'admin.products-variant-item.index',
            ['productId' => $variantItem->productVariant->product_id, 'variantId' => $variantItem->product_variant_id]
        );
    }
    public function destroy(string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        $variantItem->delete();

        return response(['status' => 'success', 'message' => 'Xóa biến thể thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $variantItem = ProductVariantItem::findOrFail($request->id);
        $variantItem->status = $request->status;
        $variantItem->save();

        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
}
