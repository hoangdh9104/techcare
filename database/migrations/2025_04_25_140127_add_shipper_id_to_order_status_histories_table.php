<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->foreignId('shipper_id')
                  ->nullable()
                  ->after('updated_by')
                  ->constrained('shippers') // hoặc 'shippers' nếu bạn có bảng riêng
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
        });
    }
};
