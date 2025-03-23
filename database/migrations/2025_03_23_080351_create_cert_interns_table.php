<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cert_interns', function (Blueprint $table) {
            $table->id('cert_id');
            $table->foreignId('intern_id')->constrained('interns', 'intern_id')->onDelete('cascade');
            $table->string('cert_name')->nullable();
            $table->float('score')->nullable();
            $table->string('cert_image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cert_intern');
    }
};