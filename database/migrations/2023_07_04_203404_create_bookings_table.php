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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->unSignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unSignedBigInteger('bookedBy')->foreign()->references('id')->on('users')->nullable();
            $table->foreign('bookedBy')->references('id')->on('users');

            $table->unSignedBigInteger('suggestedPrice');
            $table->unSignedBigInteger('ride_id')->foreign()->references('id')->on('rides');

            $table->dateTime('validatedAt');
            $table->string('payment');
            $table->dateTime('paidAt');
            $table->float('fee');
            $table->boolean('isValidated')->default(false);
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
