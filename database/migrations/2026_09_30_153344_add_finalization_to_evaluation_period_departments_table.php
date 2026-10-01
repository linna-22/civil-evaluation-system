<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_period_departments', function (Blueprint $table) {
            $table->enum('review_status', ['reviewing', 'finalized'])
                ->default('reviewing')
                ->after('department_id');

            $table->unsignedBigInteger('finalized_by')
                ->nullable()
                ->after('review_status');

            $table->timestamp('finalized_at')
                ->nullable()
                ->after('finalized_by');

            $table->foreign('finalized_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_period_departments', function (Blueprint $table) {
            $table->dropForeign(['finalized_by']);
            $table->dropColumn([
                'review_status',
                'finalized_by',
                'finalized_at',
            ]);
        });
    }
};
