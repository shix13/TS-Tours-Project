<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            // Add the new columns
            $table->text('note')->nullable();
            $table->decimal('do_pu', 8, 2)->nullable();
            $table->integer('rentPerDayHrs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            // Remove the columns if you ever need to rollback
            $table->dropColumn('note');
            $table->dropColumn('do_pu');
            $table->dropColumn('rentPerDayHrs');
        });
    }
};
