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
        Schema::create('product_variant_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Tên biến thể (Ví dụ: Màu sắc, Kích thước...)
            $table->json('value'); // Giá trị biến thể dưới dạng JSON
            $table->decimal('price', 10, 2)->nullable(); // Giá của biến thể
            $table->integer('quantity')->default(0); // Số lượng tồn kho
            $table->enum('status', ['1', '0'])->default('1'); // Trạng thái (Hiển thị / Ẩn)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variant_combinations');
    }
};
