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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('experience_year')->nullable();
            $table->string('cv')->nullable();
            $table->boolean('is_finding_job')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};