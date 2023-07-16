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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('user_id')->foreign()->references('id')->on('users');       
            $table->string('designation');
            $table->longText('description')->nullable();
            $table->string('imageName')->nullable();
            
            $table->boolean('isMusicAllowed')->default(false);
            $table->boolean('isAnimalAllowed')->default(false);
            $table->boolean('isBagAllowed')->default(false);
            $table->boolean('isFoodAllowed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
