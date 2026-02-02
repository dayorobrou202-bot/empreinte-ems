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
            // Ajout de la colonne pour stocker le total d'heures travaillées par jour
            // On utilise decimal(5,2) pour pouvoir stocker par ex: 08.50 heures
            if (!Schema::hasColumn('presences', 'total_heures')) {
                $table->decimal('total_heures', 5, 2)->default(0)->after('heure_soir');
            }

            // Ajout de la colonne date_pointage si elle n'existe pas (pour le filtrage)
            if (!Schema::hasColumn('presences', 'date_pointage')) {
                $table->date('date_pointage')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn(['total_heures', 'date_pointage']);
        });
    }
};