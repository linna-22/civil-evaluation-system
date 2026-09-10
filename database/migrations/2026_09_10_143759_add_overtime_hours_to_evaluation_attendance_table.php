<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_attendance', function (Blueprint $table) {
            $table->decimal('overtime_hours', 8, 2)
                ->default(0)
                ->after('leave_early_hours');
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_attendance', function (Blueprint $table) {
            $table->dropColumn('overtime_hours');
        });
    }
};