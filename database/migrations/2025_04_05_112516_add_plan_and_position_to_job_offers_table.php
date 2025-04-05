<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            // Nếu đã có dữ liệu cần nullable để không bị lỗi khi migrate
            $table->foreignId('recruitment_plan_id')
                ->nullable()
                ->after('department_id')
                ->constrained('recruitment_plans', 'plan_id')
                ->nullOnDelete();

            $table->foreignId('position_id')
                ->nullable()
                ->after('recruitment_plan_id')
                ->constrained('recruitment_positions', 'position_id')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropForeign(['recruitment_plan_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['recruitment_plan_id', 'position_id']);
        });
    }
};