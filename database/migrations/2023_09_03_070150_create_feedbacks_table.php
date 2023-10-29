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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('feedbackID'); // Auto-incrementing primary key
            $table->unsignedBigInteger('rentID'); // Foreign key to rents table
            $table->text('feedback_Message');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('rentID')->references('rentID')->on('rents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
