<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('weekly_scores')) {
            Schema::create('weekly_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('week_number');
                $table->integer('year');
                $table->decimal('points_presence', 5, 2)->default(0);
                $table->decimal('points_tasks', 5, 2)->default(0);
                $table->decimal('points_collaboration', 5, 2)->default(0);
                $table->decimal('score', 5, 2)->default(0); 
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_scores');
    }
};