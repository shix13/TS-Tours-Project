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
        Schema::create('rents', function (Blueprint $table) {
            $table->id('rentID'); // Auto-incrementing primary key
            $table->unsignedBigInteger('reserveID'); // Foreign key to reservations table
            $table->unsignedBigInteger('driverID')->nullable(); // Foreign key to employees table (driver)
            $table->string('rent_Period_Status');
            $table->integer('extra_Hours')->nullable();
            $table->string('payment_Status');
            $table->decimal('total_Price', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('reserveID')->references('reserveID')->on('booking');
            $table->foreign('driverID')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
