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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->string('start');
            $table->string('end');
            $table->string('price');
            $table->string('startAt');
            $table->unSignedBigInteger('user_id')->foreign()->references('id')->on('users');
            $table->boolean('status')->default(false);
            $table->string('type')->default("conducteur");
            $table->decimal('placesNumber');
            $table->decimal('passengerNumber');
            $table->boolean('twoPlaces')->default(false);
            $table->boolean('acceptAuctions')->default(false);
            $table->boolean('isDetourAllowed')->default(false);

            $table->boolean('isAnimalAllowed')->default(true);
            $table->boolean('isBagAllowed')->default(true);
            $table->boolean('isMusicAllowed')->default(true);
            $table->boolean('isFoodAllowed')->default(true);

            $table->boolean('canBook')->default(false);
            $table->decimal('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
