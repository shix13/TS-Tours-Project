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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->unsignedInteger('rating')->default(0); // Add 'rating' column with a default value of 0
        });
    }

    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
