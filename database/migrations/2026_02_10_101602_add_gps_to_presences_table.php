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
        Schema::table('presences', function (Blueprint $table) {
            // Ajout des colonnes pour la position à l'arrivée
            $table->decimal('latitude_entree', 10, 8)->nullable()->after('type');
            $table->decimal('longitude_entree', 11, 8)->nullable()->after('latitude_entree');
            
            // Ajout des colonnes pour la position au départ
            $table->decimal('latitude_sortie', 10, 8)->nullable()->after('heure_soir');
            $table->decimal('longitude_sortie', 11, 8)->nullable()->after('latitude_sortie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            // On supprime les colonnes si on annule la migration
            $table->dropColumn(['latitude_entree', 'longitude_entree', 'latitude_sortie', 'longitude_sortie']);
        });
    }
};