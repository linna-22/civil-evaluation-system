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
        Schema::create('evaluations', function (Blueprint $table) {

            $table->id('evaluation_id');

            // ==========================================
            // Evaluation Period
            // ==========================================

            $table->unsignedBigInteger('evaluation_period_id');

            // ==========================================
            // Evaluator & Evaluatee
            // ==========================================

            $table->unsignedBigInteger('evaluator_id');

            $table->unsignedBigInteger('evaluatee_id');

            // ==========================================
            // Evaluation Status
            // ==========================================

            $table->enum('evaluation_type', [
                'behavior',
                'work_performance',
                'attendance'
            ]);
            $table->enum('evaluation_status', [
                'not_submitted',
                'submitted'
            ])->default('not_submitted');

            $table->timestamp('submitted_at')->nullable();

            // ==========================================
            // Audit Fields
            // ==========================================

            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // ==========================================
            // Foreign Keys
            // ==========================================

            $table->foreign('evaluation_period_id')
                ->references('evaluation_period_id')
                ->on('evaluation_periods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('evaluator_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('evaluatee_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};