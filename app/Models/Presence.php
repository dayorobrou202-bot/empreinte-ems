<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_pointage',
        'date',
        'present',
        'heure_matin',
        'heure_midi',
        'heure_soir',
        'type',
    ];

    protected $casts = [
        'present' => 'boolean',
        'date_pointage' => 'date',
        'date' => 'date',
        'heure_matin' => 'string',
        'heure_midi' => 'string',
        'heure_soir' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
