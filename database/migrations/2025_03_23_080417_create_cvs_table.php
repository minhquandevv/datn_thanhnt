<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id('cv_id');
            $table->foreignId('candidate_id')->constrained('candidates', 'id')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('recruitment_positions', 'position_id')->onDelete('cascade');
            $table->enum('status', ['pending', 'reviewing', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
}; 