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
        Schema::create('issue_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->integer('total_issued_male')->length(11)->nullable();
            $table->integer('total_issued_female')->length(11)->nullable();
            $table->integer('total_issued_pups')->length(11)->nullable();
            $table->integer('total_homo_male')->length(11)->nullable();
            $table->integer('total_homo_female')->length(11)->nullable();
            $table->integer('total_homo_pups')->length(11)->nullable();
            $table->integer('total_hetro_male')->length(11)->nullable();
            $table->integer('total_hetro_female')->length(11)->nullable();
            $table->integer('total_hetro_pups')->length(11)->nullable();
            $table->integer('issued_male')->length(11)->nullable();
            $table->integer('issued_female')->length(11)->nullable();
            $table->integer('issued_pups')->length(11)->nullable();
            $table->integer('total')->length(11)->nullable();
            $table->integer('homo_male')->length(11)->nullable();
            $table->integer('homo_female')->length(11)->nullable();
            $table->integer('hetro_male')->length(11)->nullable();
            $table->integer('hetro_female')->length(11)->nullable();
            $table->integer('wild_male')->length(11)->nullable();
            $table->integer('wild_female')->length(11)->nullable();
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
        Schema::dropIfExists('issue_stocks');
    }
};
