<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToProductVariantCombinations extends Migration
{
    public function up()
    {
        Schema::table('product_variant_combinations', function (Blueprint $table) {
            // Thêm cột image kiểu string để lưu trữ đường dẫn tới ảnh
            $table->string('image')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('product_variant_combinations', function (Blueprint $table) {
            // Xóa cột image khi rollback
            $table->dropColumn('image');
        });
    }
}
