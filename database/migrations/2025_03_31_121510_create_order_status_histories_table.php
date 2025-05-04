<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('status');
            $table->text('reason')->nullable(); // Lưu lý do hủy đơn
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Ai cập nhật
            $table->timestamp('changed_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_histories');
    }
};
