<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'content',
        'is_group_message',
        'is_read',
        'document_path',
    ];

    /**
     * Relation avec l'expéditeur (Celui qui envoie)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le destinataire (Celui qui reçoit)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}