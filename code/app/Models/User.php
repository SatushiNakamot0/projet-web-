<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'statut',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Nwerriw l'auth dyal Laravel chno howa l7a9l dyal lmot de passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
        ];
    }

    // ========================
    // RELATIONS
    // ========================

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_utilisateur');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'id_expediteur');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'id_destinataire');
    }

    // ========================
    // HELPERS
    // ========================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMembre(): bool
    {
        return $this->role === 'membre' && $this->statut === 'actif';
    }
}
