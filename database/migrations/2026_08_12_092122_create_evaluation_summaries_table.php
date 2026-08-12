<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_summaries', function (Blueprint $table) {

            $table->id('evaluation_summary_id');

            // Participant in the evaluation period
            $table->unsignedBigInteger('evaluation_period_user_id');

            // Section scores
            $table->decimal('work_performance_score', 5, 2)->nullable();
            $table->decimal('attendance_score', 5, 2)->nullable();
            $table->decimal('behavior_score', 5, 2)->nullable();

            // Combined result
            $table->decimal('total_score', 5, 2)->nullable();

            // When the summary was calculated/updated
            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();

            // Relationship
            $table->foreign('evaluation_period_user_id')
                ->references('evaluation_period_user_id')
                ->on('evaluation_period_users')
                ->cascadeOnDelete();

            // One summary per participant per evaluation period
            $table->unique('evaluation_period_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_summaries');
    }
};