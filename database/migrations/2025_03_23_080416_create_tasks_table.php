<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->foreignId('intern_id')->constrained('interns', 'intern_id')->onDelete('cascade');
            $table->string('project_name')->nullable();
            $table->string('task_name')->nullable();
            $table->text('requirements')->nullable();
            $table->string('attachment')->nullable();
            $table->date('assigned_date')->nullable();
            $table->foreignId('assigned_by')->constrained('mentors', 'mentor_id');
            $table->enum('status', ['Chưa bắt đầu', 'Đang thực hiện', 'Hoàn thành', 'Trễ hạn'])->nullable();
            $table->text('result')->nullable();
            $table->text('mentor_comment')->nullable();
            $table->enum('evaluation', ['Rất tốt', 'Tốt', 'Trung bình', 'Kém'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};