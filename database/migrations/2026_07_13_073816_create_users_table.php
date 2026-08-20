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
        Schema::create('users', function (Blueprint $table) {

            // Primary Key
            $table->id('user_id');
            // Foreign Keys
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained(
                    table: 'organizations',
                    column: 'organization_id'
                )
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('department_id')
                ->nullable()
                ->constrained(
                    table: 'departments',
                    column: 'department_id'
                )
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            // Employee Information
            $table->string('id_code', 30)->nullable();
            $table->string('name_en', 150);
            $table->string('name_kh', 150);
            $table->string('username', 50)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('phone', 20)->nullable();
            $table->string('email')->unique();
            $table->string('position', 100)->nullable();
            $table->boolean('is_leader')->default(false);
            // Authentication
            $table->string('password');
            // Authorization
            $table->enum('role', [
                'super_admin',
                'organization_admin',
                'department_admin',
                'user'
            ]);
            $table->enum('status', ['active', 'inactive'])
                ->default('active');
            $table->timestamp('last_login')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
