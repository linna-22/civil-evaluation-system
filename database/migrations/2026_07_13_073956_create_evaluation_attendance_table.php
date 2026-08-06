<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_attendance', function (Blueprint $table) {

            $table->id('attendance_id');

            $table->foreignId('evaluation_id')
                ->constrained(
                    table: 'evaluations',
                    column: 'evaluation_id'
                )
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->integer('approved_leave_count')->default(0);
            $table->integer('unapproved_leave_count')->default(0);

            $table->decimal('late_hours', 5, 2)->default(0);
            $table->decimal('leave_early_hours', 5, 2)->default(0);

            $table->decimal('attendance_percent', 5, 2)
                ->default(100);

            $table->decimal('attendance_score', 5, 2)
                ->default(0);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_attendance');
    }
};
