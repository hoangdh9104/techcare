<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getVariantCombination(Request $request)
    {
        // Kiểm tra nhanh trạng thái sản phẩm trước
        if (!Product::where('id', $request->product_id)->where('status', 1)->exists()) {
            return response()->json(['error' => 'Sản phẩm không khả dụng'], 404);
        }

        $combination = ProductVariantCombination::where([
            'product_id' => $request->product_id,
            'sku' => $request->sku,
            'status' => 1
        ])->first();

        return $combination
            ? response()->json(['price' => $combination->price, 'quantity' => $combination->quantity])
            : response()->json(['error' => 'Biến thể không tìm thấy'], 404);
    }
}
