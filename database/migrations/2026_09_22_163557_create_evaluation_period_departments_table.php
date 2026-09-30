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
        Schema::create('evaluation_period_departments', function (Blueprint $table) {
            $table->bigIncrements('evaluation_period_department_id');

            // Evaluation period
            $table->foreignId('evaluation_period_id')
                ->constrained(
                    'evaluation_periods',
                    'evaluation_period_id'
                )
                ->cascadeOnDelete();

            // Participating department
            $table->foreignId('department_id')
                ->constrained(
                    'departments',
                    'department_id'
                )
                ->restrictOnDelete();

            $table->timestamps();

            // A department can only participate once
            // in the same evaluation period.
            $table->unique(
                ['evaluation_period_id', 'department_id'],
                'eval_period_dept_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_period_departments');
    }
};