<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('task_id')->constrained('tasks', 'task_id')->onDelete('cascade');
            $table->date('report_date')->nullable();
            $table->text('work_done')->nullable();
            $table->text('next_day_plan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_reports');
    }
};