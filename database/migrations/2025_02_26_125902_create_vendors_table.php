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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->text('banner');
            $table->string('shop_name')->nullable(); // Thêm tên shop
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->text('description');
            $table->string('fb_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->integer('user_id');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
