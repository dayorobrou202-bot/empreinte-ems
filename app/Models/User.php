<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'role_id', 'position', 'mentor_id'
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    // Relation pour le mentor (Répare l'erreur RelationNotFound)
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // Vérification Admin
    public function isAdmin()
    {
        $role = trim(strtolower((string)$this->role));
        return in_array($role, ['admin', 'administrateur']) || $this->role_id == 1;
    }

    // Autres relations existantes
    public function tasks() { return $this->hasMany(Task::class); }
    public function roleModel() { return $this->belongsTo(Role::class, 'role_id'); }



    // Relation pour récupérer les notes du collaborateur/stagiaire
public function performances()
{
    return $this->hasMany(Performance::class, 'user_id');
}
}