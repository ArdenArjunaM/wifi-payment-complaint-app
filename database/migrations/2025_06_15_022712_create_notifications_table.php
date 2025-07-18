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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('user_id'); // Foreign key for user
            $table->string('title'); // Notification title
            $table->text('message'); // Notification message
            $table->string('type'); // Type of notification (e.g., new_submission, status_change, etc.)
            $table->json('data')->nullable(); // Additional data (optional)
            $table->boolean('is_read')->default(false); // Status if read or not
            $table->timestamp('read_at')->nullable(); // Timestamp when read
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraint
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');

            // Indexes for performance optimization
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the notifications table
        Schema::dropIfExists('notifications');
    }
};
