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
        Schema::rename('vehicules_utilisation', 'vehicule_utilisations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('vehicule_utilisations', 'vehicules_utilisation');
    }
};
