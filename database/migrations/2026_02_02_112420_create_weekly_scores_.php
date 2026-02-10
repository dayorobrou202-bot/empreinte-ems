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
        // On vérifie si la table n'existe pas déjà pour éviter les erreurs
        if (!Schema::hasTable('weekly_scores')) {
            Schema::create('weekly_scores', function (Blueprint $table) {
                $table->id();
                // Lien avec l'employé
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                
                // Pour filtrer par semaine (Classement Hebdomadaire)
                $table->integer('week_number');
                $table->integer('year');

                // Les différents points qui forment le score
                $table->decimal('points_presence', 5, 2)->default(0);
                $table->decimal('points_tasks', 5, 2)->default(0);
                $table->decimal('points_collaboration', 5, 2)->default(0);
                
                // Le score final sur 10 (utilisé pour le classement)
                $table->decimal('score', 5, 2)->default(0); 

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_scores');
    }
};