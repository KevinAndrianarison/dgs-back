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
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('numero_reference')->nullable();
            $table->text('caracteristiques')->nullable();
            $table->string('marque')->nullable();
            $table->string('numero_serie')->nullable();
            $table->string('numero_imei')->nullable();
            $table->decimal('montant', 12, 2)->nullable();
            $table->date('date_acquisition')->nullable();
            $table->date('date_transfert')->nullable();
            $table->string('lieu_affectation')->nullable();
            $table->string('etat')->nullable();
            $table->foreignId('categorie_id')->constrained('categories');
            $table->foreignId('type_id')->constrained('types_materiels');
            $table->foreignId('source_id')->nullable()->constrained('sources');
            $table->foreignId('reference_id')->nullable()->constrained('references');
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('responsable_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
