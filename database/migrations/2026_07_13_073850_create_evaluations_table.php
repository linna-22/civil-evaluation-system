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
        Schema::create('evaluations', function (Blueprint $table) {

            $table->id('evaluation_id');

            $table->foreignId('user_id')
                ->constrained(
                    table: 'users',
                    column: 'user_id'
                )
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedTinyInteger('evaluation_month');

            $table->year('evaluation_year');

            $table->enum('evaluation_status', [
                'not_submitted',
                'submitted'
            ])->default('not_submitted');

            $table->timestamp('submitted_at')->nullable();

            $table->decimal('work_performance_score', 5, 2)->default(0);
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('behavior_score', 5, 2)->default(0);
            $table->decimal('total_score', 5, 2)->default(0);

            $table->timestamps();

            $table->unique([
                'user_id',
                'evaluation_month',
                'evaluation_year'
            ]);

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
