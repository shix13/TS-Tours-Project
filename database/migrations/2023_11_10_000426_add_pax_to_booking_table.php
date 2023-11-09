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
        Schema::table('booking', function (Blueprint $table) {
            $table->integer('pax')->default(1); // Change the data type or default value as needed
        });
    }

    public function down()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn('pax');
        });
    }
};
