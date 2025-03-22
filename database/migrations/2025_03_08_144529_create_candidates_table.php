<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Candidates table
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('identity_number')->unique();
            $table->string('fullname');
            $table->string('password');
            $table->date('dob')->nullable();
            $table->string('location')->nullable();
            $table->string('image_company')->nullable();
            $table->string('identity_image')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('experience_year')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('candidate_profile_id')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('r_email')->nullable();
            $table->string('r_relate')->nullable();
            $table->string('r_position')->nullable();
            $table->string('r_company')->nullable();
            $table->string('url_avatar')->nullable();
            $table->boolean('finding_job')->default(false);
            $table->timestamps();
        });

        // Candidate Profiles
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_code')->unique();
            $table->string('url_cv')->nullable();
            $table->timestamps();
        });

        // Candidate Skills
        Schema::create('candidate_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('skill_name');
            $table->text('skill_desc')->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Candidate Languages
        Schema::create('candidate_language', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Candidate Desires
        Schema::create('candidate_desires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->decimal('pay_from', 10, 2)->nullable();
            $table->decimal('pay_to', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('name');
            $table->date('date')->nullable();
            $table->string('result')->nullable();
            $table->string('location')->nullable();
            $table->string('url_cert')->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Education
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('level')->nullable();
            $table->string('edu_type')->nullable();
            $table->string('department')->nullable();
            $table->string('school_name')->nullable();
            $table->string('graduate_level')->nullable();
            $table->date('graduate_date')->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Experience
        Schema::create('experience', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_working')->default(false);
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        // Currency
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code')->unique();
            $table->decimal('exchange', 10, 2)->nullable();
            $table->string('currency_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency');
        Schema::dropIfExists('experience');
        Schema::dropIfExists('education');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('candidate_desires');
        Schema::dropIfExists('candidate_language');
        Schema::dropIfExists('candidate_skills');
        Schema::dropIfExists('candidate_profiles');
        Schema::dropIfExists('candidates');
    }
};
