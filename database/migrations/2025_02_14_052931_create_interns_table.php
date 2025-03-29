<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id('intern_id');
            $table->string('fullname')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Nam', 'Nữ', 'Khác'])->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->text('address')->nullable();
            $table->string('citizen_id')->unique();
            $table->string('citizen_id_image')->nullable();
            $table->string('degree')->nullable();
            $table->string('degree_image')->nullable();
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('mentor_id')->nullable()->constrained('mentors', 'mentor_id')->onDelete('set null');        
            $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interns');
    }
};