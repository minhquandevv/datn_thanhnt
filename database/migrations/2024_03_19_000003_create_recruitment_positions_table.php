<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_positions', function (Blueprint $table) {
            $table->id('position_id');
            $table->foreignId('plan_id')->constrained('recruitment_plans', 'plan_id')->onDelete('cascade');
            $table->string('name');
            $table->integer('quantity');
            $table->text('requirements');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_positions');
    }
}; 