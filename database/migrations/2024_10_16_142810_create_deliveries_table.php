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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('breeding_id')->nullable();
            $table->date('date_of_delivery');
            $table->integer('cage_no')->length(11);
            $table->integer('delivery_females')->length(11);
            $table->integer('pups_born')->length(11);
            $table->integer('pups_in_stock')->length(11)->nullable();
            $table->integer('pups_issued')->length(11)->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('breeding_id')->references('id')->on('breedings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
