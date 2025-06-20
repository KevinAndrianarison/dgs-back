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
        Schema::table('materiels', function (Blueprint $table) {
            //
            $table->decimal('valeur_net', 12, 2)->nullable()->after('montant');
            $table->decimal('taux_amortissement', 5, 2)->nullable()->after('valeur_net');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiels', function (Blueprint $table) {
            //
            $table->dropColumn('valeur_net');
            $table->dropColumn('taux_amortissement');
        });
    }
};
