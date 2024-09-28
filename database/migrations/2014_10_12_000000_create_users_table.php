<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Define a new migration using an anonymous class
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'users' table with various columns
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 50)->unique(); // Unique email constraint
            $table->string('phone', 50);
            $table->string('password', 100);
            $table->string('otp', 10);
            $table->timestamp('created_at')->useCurrent(); // Set 'created_at' timestamp to current time
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // Set 'updated_at' timestamp to current time on update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'users' table if the migration is rolled back
        Schema::dropIfExists('users');
    }
};