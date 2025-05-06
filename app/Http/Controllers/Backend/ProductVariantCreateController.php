<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantCombination;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Str;

class ProductVariantCreateController extends Controller
{
    use ImageUploadTrait;
    public function create(string $id)
    {
        // Trả về view tạo biến thể
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.product.create-variant-product', compact('product'));
    }
    public function store(Request $request, $productId)
    {
        // Validate dữ liệu từ form
        $validatedData = $request->validate([
            'image' => ['required', 'image', 'max:3000'],
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:1,0',
            'attributes' => 'required|array',
            'attributes.*' => 'required|array|min:1|max:1',
        ], [
            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 3MB.',
            'price.required' => 'Giá là bắt buộc.',
            'price.numeric' => 'Giá phải là một số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái phải là 1 hoặc 0.',
            'attributes.required' => 'Các thuộc tính là bắt buộc.',
            'attributes.array' => 'Các thuộc tính phải là một mảng.',
            'attributes.*.required' => 'Bạn phải chọn ít nhất một giá trị cho mỗi thuộc tính.',
            'attributes.*.array' => 'Mỗi thuộc tính phải là một mảng.',
            'attributes.*.min' => 'Bạn cần chọn ít nhất 1 giá trị cho mỗi thuộc tính.',
            'attributes.*.max' => 'Chỉ được chọn 1 giá trị cho mỗi thuộc tính.',
        ]);

        $product = Product::findOrFail($productId);
        // ✅ Lấy danh sách các thuộc tính yêu cầu của sản phẩm
        $requiredAttributes = ProductVariant::where('product_id', $productId)->pluck('name')->toArray();

        $attributes = $request->input('attributes');
        $selectedAttributes = array_keys($attributes);

        // ✅ Kiểm tra người dùng có chọn đủ tất cả thuộc tính không
        $missingAttributes = array_diff($requiredAttributes, $selectedAttributes);

        if (!empty($missingAttributes)) {
            return back()->withErrors([
                'attributes' => 'Bạn phải chọn đủ tất cả các thuộc tính: ' . implode(', ', $missingAttributes),
            ])->withInput();
        }
        // Tạo tên biến thể từ attributes
        $attributes = $request->input('attributes');
        $attributeNames = [];
        foreach ($attributes as $key => $value) {
            $attributeNames[] = $key . ': ' . $value[0];
        }
        sort($attributeNames);
        $variantName = implode(' | ', $attributeNames);

        // Kiểm tra biến thể đã tồn tại chưa
        $exists = ProductVariantCombination::where('product_id', $productId)
            ->where('name', $variantName)
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Biến thể này đã tồn tại.'])->withInput();
        }

        // ✅ Tính tổng số lượng của các biến thể đã tồn tại
        $totalVariantQty = ProductVariantCombination::where('product_id', $productId)->sum('quantity');

        // ✅ Kiểm tra xem tổng số lượng sau khi thêm biến thể mới có vượt tồn kho không
        if ($totalVariantQty + $validatedData['quantity'] > $product->qty) {
            $available = $product->qty - $totalVariantQty;
            return back()->withErrors([
                'quantity' => "Chỉ có thể thêm tối đa {$available} biến thể sản phẩm cho sản phẩm này."
            ])->withInput();
        }
        // ✅ Tạo SKU tự động từ các thuộc tính
        $baseSku = strtoupper(Str::slug(Str::limit($product->name, 10, ''), '')); // Tên sản phẩm viết tắt (VD: AOTHUN)
        $attributeCodes = [];

        foreach ($attributes as $key => $value) {
            // Lấy giá trị thuộc tính và tạo mã SKU, chỉ lấy giá trị đầu tiên (vì bạn dùng min:1|max:1 trong validate)
            $attributeCodes[] = strtoupper(Str::slug($value[0], '')); // Tạo mã SKU như 'RED', 'M', 'XL'
        }

        // Tạo SKU như: AOTHUN-RED-M
        $sku = $baseSku . '-' . implode('-', $attributeCodes);

        // ✅ Kiểm tra SKU có trùng không
        $skuExists = ProductVariantCombination::where('sku', $sku)->exists();
        if ($skuExists) {
            return back()->withErrors(['sku' => 'SKU đã tồn tại.'])->withInput();
        }
        // Xử lý hình ảnh (nếu có)
        $imagePath = $this->uploadImage($request, 'image', 'uploads');
        // xử lý sku trước khi thêm bản
        // Lưu vào bảng product_variant_combinations


        $variantCombination = ProductVariantCombination::create([
            'product_id' => $productId,
            'sku' => $sku,
            'name' => $variantName,
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'status' => $validatedData['status'],
            'image' => $imagePath,
            'value' => json_encode($attributes),
        ]);

        // ✅ Không cần trừ tồn kho sản phẩm mẹ nếu bạn dùng tồn kho theo biến thể
        // Hoặc nếu bạn vẫn muốn cập nhật tồn kho sản phẩm mẹ:
        // $product->qty -= $validatedData['quantity'];
        // $product->save();

        return redirect()->route('admin.products.show', $productId)
            ->with('success', 'Biến thể sản phẩm đã được tạo thành công.');
    }
    public function changeStatus(Request $request)
    {
        $product = ProductVariantCombination::findOrFail($request->id);
        $product->status = $request->status;
        $product->save();
        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
    public function edit(string $combinationId)
    {
        $productCombination = ProductVariantCombination::findOrFail($combinationId);
        return view('admin.product.edit-variant-product', compact('productCombination'));
    }
    public function update(Request $request, string $combinationId)
    {
        $productCombination = ProductVariantCombination::findOrFail($combinationId);
        $product = Product::findOrFail($productCombination->product_id);
        // Validate dữ liệu từ form

        $validatedData = $request->validate([

            'price' => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
        ], [
            'price.required' => 'Giá là bắt buộc.',
            'price.numeric' => 'Giá phải là một số.',
            'price.min' => 'Giá phải lớn hơn 0.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);
        // ✅ Tính tổng số lượng của các biến thể đã tồn tại
        $totalVariantQty = ProductVariantCombination::where('product_id', $product->id)->sum('quantity');
        // dd($totalVariantQty);
        // ✅ Lấy số lượng cũ của biến thể đang chỉnh sửa
        $currentVariantQty = $productCombination->quantity;

        // ✅ Tính lại tổng tồn kho sau khi cập nhật biến thể
        $newTotalVariantQty = $totalVariantQty - $currentVariantQty + $validatedData['quantity'];

        if ($newTotalVariantQty > $product->qty) {
            $available = $product->qty - ($totalVariantQty);
            return redirect()->route('admin.variants.edit', $combinationId)->withErrors([
                'quantity' => "Tổng tồn kho cho phép là {$product->qty}. Bạn chỉ có thể nhập tối đa {$available} cho biến thể này."
            ])->withInput();
        }

        $imagePath = $this->updateImage($request, 'image', 'uploads', $productCombination->image);
        $image = empty(!$imagePath) ? $imagePath : $productCombination->image;

        // ✅ Cập nhật biến thể
        $productCombination->update([
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'image' => $image,
        ]);
        return redirect()->route('admin.products.show', $product->id)->with('success', 'Cập nhật thành công.');;
    }
}
