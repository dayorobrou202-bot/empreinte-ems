<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // L'expéditeur du message
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            // Le destinataire (nullable pour le canal général)
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade'); 
            
            // Le contenu du message
            $table->text('content');
            
            // Un indicateur pour le groupe général
            $table->boolean('is_group_message')->default(false); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
