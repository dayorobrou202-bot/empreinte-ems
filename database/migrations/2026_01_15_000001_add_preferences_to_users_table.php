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
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme')->default('light')->after('password'); // light, dark
            $table->string('color_primary')->default('blue')->after('theme'); // couleur primaire
            $table->string('sidebar_position')->default('left')->after('color_primary'); // left, right
            $table->boolean('show_statistics')->default(true)->after('sidebar_position');
            $table->boolean('show_performance_chart')->default(true)->after('show_statistics');
            $table->integer('items_per_page')->default(10)->after('show_performance_chart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'theme',
                'color_primary',
                'sidebar_position',
                'show_statistics',
                'show_performance_chart',
                'items_per_page'
            ]);
        });
    }
};
