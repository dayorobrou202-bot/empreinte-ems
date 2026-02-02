<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'position',   // Ajouté pour le poste (Stagiaire, etc.)
        'mentor_id',  // Ajouté pour l'assignation au collaborateur
    ];

    /**
     * Les attributs cachés pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Vérifie si l'utilisateur est Admin.
     */
    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    /**
     * Relation : Le stagiaire appartient à un Mentor (Collaborateur).
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Relation : Un collaborateur peut avoir plusieurs stagiaires sous sa responsabilité.
     */
    public function interns()
    {
        return $this->hasMany(User::class, 'mentor_id');
    }

    /**
     * Casts pour les types de données.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}