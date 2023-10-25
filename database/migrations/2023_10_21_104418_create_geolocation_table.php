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
        Schema::create('geolocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unitID');
            $table->unsignedBigInteger('driverID');
            $table->double('latitude', 10, 7);
            $table->double('longitude', 10, 7);
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('unitID')->references('unitID')->on('vehicles');
            $table->foreign('driverID')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geolocation');
    }
};
