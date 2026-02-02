<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('presences', 'type')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->string('type')->nullable()->after('present')->comment('Bureau ou Télétravail');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('presences', 'type')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
