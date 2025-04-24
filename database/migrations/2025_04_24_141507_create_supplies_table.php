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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('region_id')->constrained('regions');
            $table->date('date');
            $table->string('rubrique');
            $table->integer('stock_initial')->nullable();
            $table->integer('entree')->nullable();
            $table->integer('sortie')->nullable();
            $table->integer('stock_final')->nullable();
            $table->string('numero_be')->nullable();
            $table->string('lieu_destination')->nullable();
            $table->string('transporteur')->nullable();
            $table->string('receptionnaire')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
