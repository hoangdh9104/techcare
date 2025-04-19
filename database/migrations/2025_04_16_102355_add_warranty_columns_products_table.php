<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->string('warranty_code')->nullable()->unique(); // Mã bảo hành duy nhất
            $table->integer('warranty_duration')->nullable(); // Thời gian bảo hành (tháng)
        $table->date('warranty_expiration_date')->nullable(); // Ngày hết hạn bảo hành
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['warranty_code']); // Xóa ràng buộc duy nhất
        $table->dropColumn(['warranty_code', 'warranty_expiration_date']); // Xóa các cột
        });
    }
};
