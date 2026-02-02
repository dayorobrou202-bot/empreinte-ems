<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('presence_logs') && !Schema::hasColumn('presence_logs', 'type')) {
            Schema::table('presence_logs', function (Blueprint $table) {
                $table->string('type')->nullable()->after('slot');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('presence_logs') && Schema::hasColumn('presence_logs', 'type')) {
            Schema::table('presence_logs', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
