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
        Schema::create('vehicules_utilisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained('materiels')->onDelete('cascade');
            $table->date('date');
            $table->string('chef_missionnaire');
            $table->string('lieu');
            $table->string('activite');
            $table->string('carburant');
            $table->string('immatriculation');
            $table->integer('km_depart');
            $table->integer('km_arrivee');
            $table->integer('total_km');
            $table->decimal('qtt_litre', 8, 2);
            $table->decimal('pu_ariary', 12, 2);
            $table->decimal('montant', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules_utilisation');
    }
};
