<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_plans', function (Blueprint $table) {
            $table->id('plan_id');
            $table->string('name');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('assigned_to')->constrained('users');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });

        // Bảng trung gian cho quan hệ nhiều-nhiều giữa kế hoạch và trường đại học
        Schema::create('recruitment_plan_university', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('recruitment_plans', 'plan_id')->onDelete('cascade');
            $table->foreignId('university_id')->constrained('universities', 'university_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_plan_university');
        Schema::dropIfExists('recruitment_plans');
    }
}; 