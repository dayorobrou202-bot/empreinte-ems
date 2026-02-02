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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');            // Nom de la mission
            $table->text('description')->nullable(); // Détails
            $table->date('due_date');           // Date limite
            $table->enum('status', ['à faire', 'en cours', 'terminé'])->default('à faire');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Collaborateur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
