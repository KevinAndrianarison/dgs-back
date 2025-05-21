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
        Schema::create('details_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained('supplies');
            $table->string('rubrique');
            $table->integer('entree');
            $table->integer('sortie');
            $table->integer('numero_be');
            $table->string('lieu_destination');
            $table->string('transporteur');
            $table->string('receptionnaire');
            $table->text('observation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_supplies');
    }
};
