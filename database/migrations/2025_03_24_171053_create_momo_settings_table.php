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
        Schema::create('momo_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status');
            $table->boolean('mode');
            $table->string('country_name');
            $table->string('currency_name');
            $table->double('currency_rate');
            $table->text('partner_code');
            $table->text('access_key');
            $table->text('secret_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momo_settings');
    }
};
