<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tasks') && !Schema::hasColumn('tasks', 'assigned_by')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tasks') && Schema::hasColumn('tasks', 'assigned_by')) {
            Schema::table('tasks', function (Blueprint $table) {
                // drop foreign key then column
                $table->dropForeign(['assigned_by']);
                $table->dropColumn('assigned_by');
            });
        }
    }
};
