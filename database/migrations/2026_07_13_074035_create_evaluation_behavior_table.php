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
        Schema::create('evaluation_behavior', function (Blueprint $table) {

            $table->id('behavior_id');

            $table->foreignId('evaluation_id')
                ->constrained(
                    table: 'evaluations',
                    column: 'evaluation_id'
                )
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('criteria_name', 150);

            $table->decimal('score', 5, 2)->default(0);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_behavior');
    }
};
