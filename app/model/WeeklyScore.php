<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_number',
        'year',
        'points_presence',
        'points_tasks',
        'points_collaboration',
        'score'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}