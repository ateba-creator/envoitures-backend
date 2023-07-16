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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->string('birthDate');
            $table->decimal('age')->nullable();
            $table->string('phoneNumber');
            $table->string('sex');
            $table->boolean('receivingNewsPapers')->default(False);
            $table->string('facebookId')->nullable();
            $table->string('googleId')->nullable();
            $table->string('imageName')->nullable();
            $table->boolean('isActive')->default(True);
            $table->string('paymentAccount')->nullable();
            $table->boolean('isAcceptedAutomatically')->default(False);
            $table->boolean('isDetourPossible')->default(False);

            $table->integer('completed')->default(50);

            $table->string('licenseImageRecto')->nullable();
            $table->dateTime('licenseRectoUpdated')->nullable();
            $table->string('licenseImageVerso')->nullable();
            $table->dateTime('licenseVersoUpdated')->nullable();

            $table->string('idCardImageRecto')->nullable();
            $table->dateTime('idCardRectoUpdated')->nullable();
            $table->string('idCardImageVerso')->nullable();
            $table->dateTime('idCardVersoUpdated')->nullable();

            $table->string('role')->default('[ROLE_USER]');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
