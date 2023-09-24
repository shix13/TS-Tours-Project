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
        //
        Schema::table('vehicles', function (Blueprint $table) {
        $table->unsignedBigInteger('vehicle_Type_ID'); // Foreign key to vehicle type table

        // Define foreign key constraints
        $table->foreign('vehicle_Type_ID')->references('vehicle_Type_ID')->on('vehicle_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
