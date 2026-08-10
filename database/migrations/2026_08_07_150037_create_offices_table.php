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
        Schema::create('offices', function (Blueprint $table) {
            $table->id('office_id');
            $table->foreignId('department_id')
                ->constrained(
                    table: 'departments',
                    column: 'department_id'
                )
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('office_name_en', 255);
            $table->string('office_name_kh', 255);
            $table->string('office_code', 255)->nullable();
            $table->string('desc', 350)->nullable();
            $table->enum('status', ['active', 'inactive'])
                ->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
