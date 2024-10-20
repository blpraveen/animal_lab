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
        Schema::create('strains', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('strain_code',255);
            $table->unsignedBigInteger('specie_id')->nullable();
            $table->text('reason')->nullable();
            $table->foreign('specie_id')->references('id')->on('species');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strains');
    }
};
