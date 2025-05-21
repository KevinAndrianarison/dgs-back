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
        Schema::table('details_supplies', function (Blueprint $table) {
            //
            $table->string('rubrique')->nullable()->change();
            $table->integer('entree')->nullable()->change();
            $table->integer('sortie')->nullable()->change();
            $table->integer('numero_be')->nullable()->change();
            $table->string('lieu_destination')->nullable()->change();
            $table->string('transporteur')->nullable()->change();
            $table->string('receptionnaire')->nullable()->change();
            $table->text('observation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details_supplies', function (Blueprint $table) {
            //
            $table->string('rubrique')->change();
            $table->integer('entree')->change();
            $table->integer('sortie')->change();
            $table->integer('numero_be')->change();
            $table->string('lieu_destination')->change();
            $table->string('transporteur')->change();
            $table->string('receptionnaire')->change();
            $table->text('observation')->change();
        });
    }
};
