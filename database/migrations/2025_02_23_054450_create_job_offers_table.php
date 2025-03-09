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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->nullOnDelete();
            $table->date('expiration_date');
            $table->text('job_detail')->nullable();
            $table->text('job_description')->nullable();
            $table->text('job_requirement')->nullable();
            $table->string('job_position')->nullable();
            $table->decimal('job_salary', 10, 2)->nullable();
            $table->integer('job_quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
