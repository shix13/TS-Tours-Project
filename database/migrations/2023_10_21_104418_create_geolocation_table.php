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
            $table->id('geolocationID');
            $table->unsignedBigInteger('assignedID');
            $table->double('latitude', 10, 7);
            $table->double('longitude', 10, 7);
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('assignedID')->references('assignedID')->on('vehicles_assigned');
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
