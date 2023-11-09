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
            $table->integer('mileage')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn('mileage');
        });
    }
};
