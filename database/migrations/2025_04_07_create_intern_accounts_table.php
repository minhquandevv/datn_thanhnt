<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intern_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns', 'intern_id')->onDelete('cascade');
            $table->string('username');
            $table->string('email');
            $table->string('password_plain'); // Lưu mật khẩu dạng plaintext
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_accounts');
    }
}; 