<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyScore extends Model
{
    // On autorise Laravel à remplir ces colonnes
    protected $fillable = [
        'user_id', 
        'score', 
        'week_number', 
        'year', 
        'points_presence', 
        'points_tasks', 
        'points_collaboration', 
        'points_manual'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}