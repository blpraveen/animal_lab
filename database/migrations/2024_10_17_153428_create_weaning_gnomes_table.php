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
        Schema::create('weaning_gnomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->integer('weaned_homo_male')->length(11);
            $table->integer('weaned_homo_female')->length(11);
            $table->integer('weaned_hetro_male')->length(11);
            $table->integer('weaned_hetro_female')->length(11);
            $table->integer('weaned_wild_male')->length(11);
            $table->integer('weaned_wild_female')->length(11);
            $table->date('date_of_weaned');
            $table->text('remarks')->nullable();
            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weaning_gnomes');
    }
};
