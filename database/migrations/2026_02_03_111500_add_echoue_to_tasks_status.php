<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'échoué' to the enum list for status
        DB::statement("ALTER TABLE `tasks` MODIFY `status` ENUM('à faire','en cours','terminé','échoué') NOT NULL DEFAULT 'à faire'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'échoué' by reverting to previous enum values
        DB::statement("ALTER TABLE `tasks` MODIFY `status` ENUM('à faire','en cours','terminé') NOT NULL DEFAULT 'à faire'");
    }
};
