<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bảng job_applications: Lưu thông tin ứng tuyển
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Người nộp đơn
            $table->foreignId('job_offer_id')->constrained('job_offers')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->string('cv'); // Đường dẫn file CV
            $table->date('application_date'); // Ngày ứng tuyển
            $table->enum('status', ['pending', 'interview_scheduled', 'approved', 'rejected'])->default('pending'); // Trạng thái
            $table->timestamps();
        });

        // Bảng application_statuses: Lưu lịch sử trạng thái của đơn ứng tuyển
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->onDelete('cascade'); 
            $table->enum('status', ['submitted', 'pending_review', 'interview_scheduled', 'result_pending', 'approved', 'rejected']);
            $table->date('status_date')->nullable(); // Ngày trạng thái
            $table->timestamps();
        });

        // Bảng application_feedbacks: Lưu phản hồi của nhà tuyển dụng
        Schema::create('application_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->onDelete('cascade');
            $table->text('feedback'); // Phản hồi từ nhà tuyển dụng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_feedbacks');
        Schema::dropIfExists('application_statuses');
        Schema::dropIfExists('job_applications');
    }
};