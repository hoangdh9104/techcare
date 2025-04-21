<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $products = Product::with('variantCombinations')->get();

        foreach ($products as $product) {
            if ($product->warranty_code && $product->warranty_expiration_date) {
                foreach ($product->variantCombinations as $variant) {
                    $variant->warranty_code = $product->warranty_code;
                    $variant->warranty_expiration_date = $product->warranty_expiration_date;
                    $variant->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variant_combinations', function (Blueprint $table) {
            //
        });
    }
};
