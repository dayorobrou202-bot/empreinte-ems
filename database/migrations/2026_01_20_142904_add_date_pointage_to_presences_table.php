<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('presences')) {
            if (!Schema::hasColumn('presences', 'date_pointage')) {
                Schema::table('presences', function (Blueprint $table) {
                    $table->date('date_pointage')->nullable()->after('user_id');
                });

                // copy values from old `date` column if present
                if (Schema::hasColumn('presences', 'date')) {
                    \DB::table('presences')->whereNull('date_pointage')->update(['date_pointage' => \DB::raw('date')]);
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('presences') && Schema::hasColumn('presences', 'date_pointage')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->dropColumn('date_pointage');
            });
        }
    }
};