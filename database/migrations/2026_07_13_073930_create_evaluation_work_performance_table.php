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
    Schema::create('evaluation_work_performance', function (Blueprint $table) {

        $table->id('work_performance_id');

        $table->foreignId('evaluation_id')
              ->constrained(
                    table:'evaluations',
                    column:'evaluation_id'
              )
              ->cascadeOnDelete()
              ->cascadeOnUpdate();

        $table->text('expected_result');

        $table->text('actual_result');

        $table->decimal('achievement_percent',5,2)
              ->default(0);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_work_performance');
    }
};
