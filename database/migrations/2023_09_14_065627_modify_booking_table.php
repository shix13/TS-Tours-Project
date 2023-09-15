<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dateTime('startDate')->change();
            $table->dateTime('endDate')->change();
        });
    }

    public function down()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->date('startDate')->change();
            $table->date('endDate')->change();
        });
    }
};
