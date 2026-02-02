<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            if (!Schema::hasColumn('presences', 'morning')) {
                $table->boolean('morning')->default(false)->after('date');
            }
            if (!Schema::hasColumn('presences', 'midday')) {
                $table->boolean('midday')->default(false)->after('morning');
            }
            if (!Schema::hasColumn('presences', 'evening')) {
                $table->boolean('evening')->default(false)->after('midday');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            if (Schema::hasColumn('presences', 'evening')) {
                $table->dropColumn('evening');
            }
            if (Schema::hasColumn('presences', 'midday')) {
                $table->dropColumn('midday');
            }
            if (Schema::hasColumn('presences', 'morning')) {
                $table->dropColumn('morning');
            }
        });
    }
};
