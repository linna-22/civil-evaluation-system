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
        Schema::create('departments', function (Blueprint $table) {

            // Primary Key
            $table->id('department_id');

            // Foreign Key
            $table->foreignId('organization_id')
                ->constrained(
                    table: 'organizations',
                    column: 'organization_id'
                )
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Main Information
            $table->string('department_code', 20);
            $table->string('department_name_kh', 150);
            $table->string('department_name_en', 150)->nullable();
            $table->string('desc', 350)->nullable();

            // System Fields
            $table->enum('status', ['active', 'inactive'])
                ->default('active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Timestamps
            $table->timestamps();

            $table->unique([
                'organization_id',
                'department_code'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
