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
        Schema::create('product_variant_items', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            // $table->integer('qty');
            // $table->string('sku')->nullable();
            // $table->double('price');
            // $table->double('offer_price')->nullable();
            // $table->date('offer_start_date')->nullable();
            // $table->date('offer_end_date')->nullable();
            // $table->string('name');
            $table->double('price');
            $table->boolean('is_default');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_items');
    }
};
