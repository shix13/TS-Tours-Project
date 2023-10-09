<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dateTime('scheduleDate')->change(); // Modify scheduleDate to dateTime
            $table->dateTime('endDate')->nullable()->change(); // Modify endDate to nullable dateTime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revert the changes if necessary
        Schema::table('maintenances', function (Blueprint $table) {
            $table->date('scheduleDate')->change(); // Change back to date if necessary
            $table->date('endDate')->nullable()->change(); // Change back to nullable date if necessary
        });
    }
};
