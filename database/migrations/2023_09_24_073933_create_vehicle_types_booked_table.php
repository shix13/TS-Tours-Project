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
        Schema::create('vehicle_types_booked', function (Blueprint $table) {
            $table->id('vehicle_Booked_ID');
            $table->unsignedBigInteger('vehicle_Type_ID'); // Foreign key to vehicle type table
            $table->unsignedBigInteger('reserveID'); // Foreign key to booking table
            $table->integer('quantity');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('vehicle_Type_ID')->references('vehicle_Type_ID')->on('vehicle_types');
            $table->foreign('reserveID')->references('reserveID')->on('booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_booked');
    }
};
