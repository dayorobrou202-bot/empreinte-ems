<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'due_date', 
        'user_id', 
        'assigned_by', 
        'status'
    ];

    // TRÈS IMPORTANT : Permet de comparer les dates sans erreur
    protected $casts = [
        'due_date' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}