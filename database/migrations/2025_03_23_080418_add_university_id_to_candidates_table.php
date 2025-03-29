<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->after('department_id')
                  ->constrained('universities', 'university_id')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });
    }
}; 