<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weekly_scores', function (Blueprint $table) {
            $table->id();
            // Lie le score à l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Les scores et points
            $table->float('score')->default(0); // Note sur 10
            $table->integer('week_number');    // Numéro de la semaine
            $table->integer('year');           // Année
            
            // Détails pour l'évolution jour après jour
            $table->float('points_presence')->default(0); 
            $table->float('points_tasks')->default(0);    
            $table->float('points_collaboration')->default(0);
            $table->float('points_manual')->default(0);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_scores');
    }
};
