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
        Schema::create('organizations', function (Blueprint $table) {

            // Primary Key
            $table->id('organization_id');

            // Main Information
            $table->string('organization_code', 20)->unique();
            $table->string('organization_name', 150);

            // System Fields
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            // Timestamps
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
