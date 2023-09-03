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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id('tariffID'); // Auto-incrementing primary key
            $table->string('location');
            $table->decimal('rate_Per_Day', 10, 2); // Decimal column with 10 total digits and 2 decimal places
            $table->decimal('rent_Per_Hour', 10, 2); // Decimal column with 10 total digits and 2 decimal places
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
