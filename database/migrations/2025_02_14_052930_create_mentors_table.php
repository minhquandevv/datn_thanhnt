<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id('mentor_id');
            $table->string('mentor_name')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id')->onDelete('set null');
            $table->string('position')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentors');
    }
};