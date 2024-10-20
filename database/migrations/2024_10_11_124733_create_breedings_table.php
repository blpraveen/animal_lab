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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->unsignedBigInteger('strain_id')->nullable();
            $table->foreign('strain_id')->references('id')->on('strains');
            $table->unsignedBigInteger('colony_id')->nullable();
            $table->foreign('colony_id')->references('id')->on('colonies');
            $table->date('date_of_ifm');
            $table->integer('breeder_male')->length(11);
            $table->integer('breeder_female')->length(11);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeding');
    }
};
