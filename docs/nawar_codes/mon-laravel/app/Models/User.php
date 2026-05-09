<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'role',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Relations ────────────────────────────────────────────────────

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }


    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'id_expediteur');
    }

    
    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'id_destinataire');
    }

    // ── Helpers ──────────────────────────────────────────────────────

    
    public function nomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->name);
    }

    
    public function initiales(): string
    {
        $initiales = strtoupper(substr($this->name, 0, 1));
        if ($this->prenom) {
            $initiales = strtoupper(substr($this->prenom, 0, 1)) . strtoupper(substr($this->name, 0, 1));
        }
        return $initiales;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMembre(): bool
    {
        return $this->role === 'membre';
    }

    public function isActif(): bool
    {
        return $this->statut === 'actif';
    }


    public function messagesNonLus(): int
    {
        return $this->messagesRecus()->where('lu', 0)->count();
    }
}