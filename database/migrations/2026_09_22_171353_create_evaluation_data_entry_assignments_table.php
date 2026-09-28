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
        Schema::create('evaluation_data_entry_assignments', function (Blueprint $table) {
            $table->bigIncrements('evaluation_data_entry_assignment_id');

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

            // Office
            // NULL = department-level assignment
            $table->foreignId('office_id')
                ->nullable()
                ->constrained(
                    'offices',
                    'office_id'
                )
                ->restrictOnDelete();

            // User responsible for entering data
            $table->foreignId('user_id')
                ->constrained(
                    'users',
                    'user_id'
                )
                ->restrictOnDelete();

            // Assignment scope
            $table->enum('scope', [
                'office',
                'department',
            ]);

            $table->timestamps();

            // Prevent duplicate assignment for the same scope
            $table->unique(
                [
                    'evaluation_period_id',
                    'department_id',
                    'office_id',
                ],
                'eval_data_entry_scope_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_data_entry_assignments');
    }
};