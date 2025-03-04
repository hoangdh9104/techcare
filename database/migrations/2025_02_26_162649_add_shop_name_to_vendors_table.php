<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Kiểm tra nếu cột shop_name chưa tồn tại thì mới thêm vào bảng vendors
        if (!Schema::hasColumn('vendors', 'shop_name')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->string('shop_name')->nullable()->after('banner');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('shop_name');
        });
    }
};
