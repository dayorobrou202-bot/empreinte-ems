<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    // Autorise l'enregistrement de ces données
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'type',
        'reason',
        'status',
    ];

    // Définit que le congé appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
