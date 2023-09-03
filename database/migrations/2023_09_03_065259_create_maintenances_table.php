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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id('maintID'); // Auto-incrementing primary key
            $table->unsignedBigInteger('unitID'); // Foreign key to vehicles table
            $table->unsignedBigInteger('empID'); // Foreign key to employees table
            $table->unsignedBigInteger('mechanicAssigned'); // Foreign key to employees table (Mechanic)
            $table->date('scheduleDate');
            $table->text('notes')->nullable();
            $table->string('status');
            $table->date('endDate')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('unitID')->references('unitID')->on('vehicles');
            $table->foreign('empID')->references('empID')->on('employees');
            $table->foreign('mechanicAssigned')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
