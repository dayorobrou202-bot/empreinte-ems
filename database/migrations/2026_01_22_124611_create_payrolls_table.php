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
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                // AJOUT DES COLONNES ICI (À l'intérieur de la fonction)
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('month'); // Ex: 2026-01
                $table->decimal('amount', 10, 2);
                $table->string('pdf_path')->nullable();
                $table->string('status')->default('paid');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
