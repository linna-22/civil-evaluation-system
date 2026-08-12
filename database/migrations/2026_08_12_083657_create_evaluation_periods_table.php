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
        Schema::create('evaluation_periods', function (Blueprint $table) {
            $table->id('evaluation_period_id');
            // Evaluation month and year
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            // Evaluation period
            $table->date('start_date');
            $table->date('end_date');
            // Evaluation status
            $table->enum('status', [
                'open',
                'closed',
            ])->default('open');
            // Super Admin who created the evaluation
            $table->unsignedBigInteger('created_by');
            // Super Admin who closed the evaluation
            $table->unsignedBigInteger('closed_by')->nullable();
            // Opening / closing timestamps
            $table->timestamp('open_at')->nullable();
            $table->timestamp('close_at')->nullable();
            $table->timestamps();
            // Relationships
            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
            $table->foreign('closed_by')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
            // Prevent duplicate evaluation month/year
            $table->unique(['month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_periods');
    }
};
