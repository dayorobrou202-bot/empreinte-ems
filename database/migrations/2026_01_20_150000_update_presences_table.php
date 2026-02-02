<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename existing `date` to `date_pointage` if present
        if (Schema::hasTable('presences')) {
            // add date_pointage column if missing and copy from existing date
            if (!Schema::hasColumn('presences', 'date_pointage')) {
                Schema::table('presences', function (Blueprint $table) {
                    $table->date('date_pointage')->nullable()->after('user_id');
                });
                // copy values from old `date` column if present
                if (Schema::hasColumn('presences', 'date')) {
                    \DB::table('presences')->whereNull('date_pointage')->update(['date_pointage' => \DB::raw('date')]);
                }
            }

            Schema::table('presences', function (Blueprint $table) {
                if (!Schema::hasColumn('presences', 'heure_matin')) {
                    $table->time('heure_matin')->nullable()->after('date_pointage');
                }
                if (!Schema::hasColumn('presences', 'heure_midi')) {
                    $table->time('heure_midi')->nullable()->after('heure_matin');
                }
                if (!Schema::hasColumn('presences', 'heure_soir')) {
                    $table->time('heure_soir')->nullable()->after('heure_midi');
                }

                if (!Schema::hasColumn('presences', 'type')) {
                    // use enum for Bureau / Télétravail
                    $table->enum('type', ['Bureau', 'Télétravail'])->nullable()->after('heure_soir');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('presences')) {
            Schema::table('presences', function (Blueprint $table) {
                if (Schema::hasColumn('presences', 'type')) {
                    $table->dropColumn('type');
                }
                if (Schema::hasColumn('presences', 'heure_soir')) {
                    $table->dropColumn('heure_soir');
                }
                if (Schema::hasColumn('presences', 'heure_midi')) {
                    $table->dropColumn('heure_midi');
                }
                if (Schema::hasColumn('presences', 'heure_matin')) {
                    $table->dropColumn('heure_matin');
                }
                // do not attempt to rename columns back on down; leave as-is or drop field
                if (Schema::hasColumn('presences', 'date_pointage')) {
                    $table->dropColumn('date_pointage');
                }
            });
        }
    }
};
