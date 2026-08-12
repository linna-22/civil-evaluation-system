<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_period_users', function (Blueprint $table) {

            $table->id('evaluation_period_user_id');
            // Evaluation period
            $table->unsignedBigInteger('evaluation_period_id');
            // Participant
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            // Relationships
            $table->foreign('evaluation_period_id')
                ->references('evaluation_period_id')
                ->on('evaluation_periods')
                ->cascadeOnDelete();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
            // A user can only be included once
            // in the same evaluation period
            $table->unique([
                'evaluation_period_id',
                'user_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_period_users');
    }
};