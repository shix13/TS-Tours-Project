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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('reserveID')->startingValue(1000000); // Auto-incrementing primary key
            $table->unsignedBigInteger('unitID'); // Foreign key to vehicles table
            $table->unsignedBigInteger('tariffID'); // Foreign key to tariffs table
            $table->date('startDate');
            $table->date('endDate');
            $table->string('mobileNum');
            $table->string('pickUp_Address');
            $table->text('note')->nullable();
            $table->decimal('downpayment_Fee', 10, 2)->nullable(); // Decimal column with 10 total digits and 2 decimal places
            $table->string('gcash_RefNum')->nullable();
            $table->decimal('subtotal', 10, 2); // Decimal column with 10 total digits and 2 decimal places
            $table->string('status');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('unitID')->references('unitID')->on('vehicles');
            $table->foreign('tariffID')->references('tariffID')->on('tariffs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
