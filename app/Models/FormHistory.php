<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormHistory extends Model
{
    protected $table = 'form_histories';

    protected $fillable = [
        'actor_id',
        'form_name',
        'route',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
