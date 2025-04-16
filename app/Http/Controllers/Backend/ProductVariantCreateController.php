<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantCombination;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\ImageUploadTrait;

class ProductVariantCreateController extends Controller
{
    use ImageUploadTrait;
    public function create(string $id)
    {
        // Trả về view tạo biến thể
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.product.create-variant-product', compact('product'));
    }
    // public function store(Request $request, $productId)
    // {
    //     // dd($request->all());
    //     // Validate dữ liệu từ form
    //     $validatedData = $request->validate([
    //         // 'name' => 'required',
    //         'price' => 'required|numeric|min:0',
    //         'quantity' => 'required|integer|min:1',
    //         'status' => 'required|in:1,0',
    //         'attributes' => 'required|array', // Xác nhận attributes là mảng
    //         'attributes.*' => 'required|array|min:1|max:1', // ✅ CHỈ 1 lựa chọn mỗi thuộc tính
    //     ], [
    //         'price.required' => 'Giá là bắt buộc.',
    //         'price.numeric' => 'Giá phải là một số.',
    //         'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
    //         'quantity.required' => 'Số lượng là bắt buộc.',
    //         'quantity.integer' => 'Số lượng phải là một số nguyên.',
    //         'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
    //         'status.required' => 'Trạng thái là bắt buộc.',
    //         'status.in' => 'Trạng thái phải là 1 hoặc 0.',
    //         'attributes.required' => 'Các thuộc tính là bắt buộc.',
    //         'attributes.array' => 'Các thuộc tính phải là một mảng.',
    //         'attributes.*.required' => 'Bạn phải chọn ít nhất một giá trị cho mỗi thuộc tính.',
    //         'attributes.*.array' => 'Mỗi thuộc tính phải là một mảng.',
    //         'attributes.*.min' => 'Bạn cần chọn ít nhất 1 giá trị cho mỗi thuộc tính.',
    //         'attributes.*.max' => 'Chỉ được chọn 1 giá trị cho mỗi thuộc tính.',
    //     ]);
    //     $attributes = $request->input('attributes');

    //     // Tạo name từ attributes
    //     $attributeNames = [];
    //     foreach ($attributes as $key => $value) {
    //         $attributeNames[] = $key . ': ' . $value[0];
    //     }
    //     sort($attributeNames);
    //     $variantName = implode(' | ', $attributeNames);

    //     // Kiểm tra name đã tồn tại chưa
    //     $exists = ProductVariantCombination::where('product_id', $productId)
    //         ->where('name', $variantName)
    //         ->exists();

    //     if ($exists) {
    //         return back()->withErrors(['name' => 'Biến thể này đã tồn tại.'])->withInput();
    //     }

    //     // Kiểm tra quantity <= qty trong kho
    //     $product = Product::findOrFail($productId);
    //     if ($request->quantity > $product->qty) {
    //         return back()->withErrors(['quantity' => 'Số lượng biến thể vượt quá tồn kho.'])->withInput();
    //     }

    //     // Xử lý hình ảnh (nếu có)
    //     $imagePath = $this->uploadImage($request, 'image', 'uploads');
    //     // Lưu vào bảng product_variant_combinations
    //     $variantCombination = ProductVariantCombination::create([
    //         'product_id' => $productId, // ID sản phẩm
    //         'name' => $variantName,
    //         'price' => $request->input('price'),
    //         'quantity' => $request->input('quantity'),
    //         'status' => $request->input('status'),
    //         'image' => $imagePath, // Lưu đường dẫn ảnh nếu có
    //         'value' => json_encode($request->input('attributes')), // Lưu dữ liệu các biến thể dưới dạng JSON
    //     ]);
    //     // Cập nhật lại số lượng tồn kho của sản phẩm
    //     $product->qty = $product->qty - $request->quantity; // Trừ số lượng bán được từ tồn kho
    //     $product->save();
    //     // Trả về thông báo thành công hoặc chuyển hướng đến trang khác
    //     return redirect()->route('admin.products.show', $productId)
    //         ->with('success', 'Biến thể sản phẩm đã được tạo thành công.');
    // }
    public function store(Request $request, $productId)
    {
        // Validate dữ liệu từ form
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:1,0',
            'attributes' => 'required|array',
            'attributes.*' => 'required|array|min:1|max:1',
        ], [
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

        // Xử lý hình ảnh (nếu có)
        $imagePath = $this->uploadImage($request, 'image', 'uploads');

        // Lưu vào bảng product_variant_combinations
        $variantCombination = ProductVariantCombination::create([
            'product_id' => $productId,
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
}
