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
        Schema::create('vnpay_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status'); // 1: bật, 0: tắt
            $table->boolean('mode'); // 1: live, 0: sandbox
            $table->string('tmn_code'); // Mã website - vnp_TmnCode
            $table->string('hash_secret'); // vnp_HashSecret
            $table->string('payment_url'); // vnp_Url
            $table->string('return_url'); // redirect sau khi thanh toán
            $table->string('ipn_url'); // IPN dùng để xác nhận giao dịch
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vnpay_settings');
    }
};
