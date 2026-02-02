<?php

// Copie de sauvegarde déplacée depuis app/model
namespace App\LegacyModels;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'amount',
        'pdf_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
