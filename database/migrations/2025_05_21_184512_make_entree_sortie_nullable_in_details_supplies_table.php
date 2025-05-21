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
            $table->integer('entree')->nullable()->change();
            $table->integer('sortie')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details_supplies', function (Blueprint $table) {
            //
            $table->integer('entree')->change();
            $table->integer('sortie')->change();
        });
    }
};
