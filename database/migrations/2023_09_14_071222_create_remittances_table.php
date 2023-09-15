<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('remittances', function (Blueprint $table) {
            $table->id('remitID');
            $table->unsignedBigInteger('clerkID');
            $table->unsignedBigInteger('driverID');
            $table->unsignedBigInteger('rentID');
            $table->string('receiptNum');
            $table->decimal('amount', 10, 2);
            $table->dateTime('date_Time');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('clerkID')->references('empID')->on('employees');
            $table->foreign('driverID')->references('empID')->on('employees');
            $table->foreign('rentID')->references('rentID')->on('rents');
        });
    }

    public function down()
    {
        Schema::dropIfExists('remittances');
    }
};
