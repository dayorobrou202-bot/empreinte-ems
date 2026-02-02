<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::table('users', function (Blueprint $table) {
        $table->string('position')->nullable()->after('name'); // Fixe ton erreur SQL
        $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null'); // Assigne au collaborateur
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
