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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('banner')->nullable();
            $table->string('shop_name')->nullable(); // Thêm tên shop
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('address');
            $table->text('description')->nullable();
            $table->string('fb_link')->nullable();   // Thêm link Facebook
            $table->string('tw_link')->nullable();   // Thêm link Twitter
            $table->string('insta_link')->nullable(); // Thêm link Instagram
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
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
