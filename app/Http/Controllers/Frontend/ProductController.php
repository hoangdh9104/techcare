<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariantCombination;

class ProductController extends Controller
{
    public function getVariantCombination(Request $request)
    {
        $sku = $request->query('sku');
        $productId = $request->query('product_id');

        $combination = ProductVariantCombination::where('product_id', $productId)
            ->where('sku', $sku)
            ->first();

        if (!$combination) {
            return response()->json(['error' => 'Biến thể không tìm thấy'], 404);
        }
        // dd($combination);
        return response()->json([
            'price' => $combination->price,
            'quantity' => $combination->quantity,
            'status' => $combination->status
        ]);
    }
}
