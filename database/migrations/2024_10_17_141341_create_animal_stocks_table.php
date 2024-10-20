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
        Schema::create('animal_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->integer('weaned_male')->length(11);
            $table->integer('weaned_female')->length(11);
            $table->integer('issued_male')->length(11)->nullable();
            $table->integer('issued_female')->length(11)->nullable();
            $table->integer('issued_pups')->length(11)->nullable();
            $table->integer('total_issued_male')->length(11)->nullable();
            $table->integer('total_issued_female')->length(11)->nullable();
            $table->integer('total_issued_pups')->length(11)->nullable();
            $table->integer('total_male')->length(11)->nullable();
            $table->integer('total_female')->length(11)->nullable();
            $table->integer('total_pups')->length(11)->nullable();
            $table->integer('total')->length(11)->nullable();
            $table->date('date_of_weaned');
            $table->boolean('is_active')->default(1);
            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_stocks');
    }
};
