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
            $table->string('org_code', 20)->unique();
            $table->string('org_name_kh', 150);
            $table->string('org_name_en', 150)->nullable();
            $table->string('desc', 350)->nullable();

            // System Fields
            $table->enum('status', ['active', 'inactive'])
                ->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

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
