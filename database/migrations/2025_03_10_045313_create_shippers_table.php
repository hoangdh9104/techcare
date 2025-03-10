<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->string('banner')->nullable();
            $table->string('name', 200);
            $table->string('phone', 50);
            $table->string('email', 200)->unique();
            $table->text('address');
            $table->text('description');
            $table->string('fb_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shippers');
    }
};
