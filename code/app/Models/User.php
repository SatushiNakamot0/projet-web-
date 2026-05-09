<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Smiya dyal la table exacte f l'MCD
    protected $table = 'utilisateurs';

    // Les colonnes lli ymken n3emrouhom
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'statut',
    ];

    // Les colonnes lli makhas'homch ybano
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Nwerriw l'auth dyal Laravel chno howa l7e9l dyal lmodepasse
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // T7wil dyal l'enwé3
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
        ];
    }

    // =========================================================
    // RELATIONS (L3alaqat)
    // =========================================================

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

    // =========================================================
    // HELPERS (Mosa3adat)
    // =========================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMembre(): bool
    {
        return $this->role === 'membre' && $this->statut === 'actif';
    }
}
