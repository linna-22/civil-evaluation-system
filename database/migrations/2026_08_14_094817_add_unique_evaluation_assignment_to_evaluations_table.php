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
        Schema::table('evaluations', function (Blueprint $table) {
             $table->unique(
                [
                    'evaluation_period_id',
                    'evaluator_id',
                    'evaluatee_id',
                ],
                'evaluations_period_evaluator_evaluatee_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
             $table->dropUnique(
                'evaluations_period_evaluator_evaluatee_unique'
            );
        });
    }
};
