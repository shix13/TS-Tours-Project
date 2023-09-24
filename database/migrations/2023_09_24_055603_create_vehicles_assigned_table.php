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
        Schema::create('vehicles_assigned', function (Blueprint $table) {
            $table->id('assignedID');
            $table->unsignedBigInteger('unitID'); // Foreign key to vehicle table
            $table->unsignedBigInteger('empID'); // Foreign key to employee table
            $table->unsignedBigInteger('rentID'); // Foreign key to rent table
            $table->timestamps();

            
            // Define foreign key constraints
            $table->foreign('unitID')->references('unitID')->on('vehicles');
            $table->foreign('empID')->references('empID')->on('employees');
            $table->foreign('rentID')->references('rentID')->on('rents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_assigned');
    }
};
