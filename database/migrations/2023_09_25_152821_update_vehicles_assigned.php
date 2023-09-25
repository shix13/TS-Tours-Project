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
        Schema::table('vehicles_assigned', function (Blueprint $table) {
            $table->unsignedBigInteger('reserveID'); // Foreign key to booking table

            // Define foreign key constraints
            $table->foreign('reserveID')->references('reserveID')->on('booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles_assigned', function (Blueprint $table) {
            $table->dropForeign(['reserveID']); // Foreign key to booking table

            // Define foreign key constraints
            $table->dropColumn('reserveID');
        });
    }
};
