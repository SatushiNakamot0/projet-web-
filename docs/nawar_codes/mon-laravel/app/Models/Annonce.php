<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categorie_id',
        'titre',
        'description',
        'prix',
        'ville',
        'image',
        'statut',
        'motif_rejet',
        'date_publication',
    ];

    protected $casts = [
        'prix'             => 'decimal:2',
        'date_publication' => 'datetime',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }


    public function photos()
    {
        return $this->hasMany(Photo::class, 'annonce_id')->orderBy('ordre');
    }

    
    public function photoprincipale()
    {
        return $this->hasOne(Photo::class, 'annonce_id')->orderBy('ordre');
    }

    
    public function messages()
    {
        return $this->hasMany(Message::class, 'id_annonce');
    }

    // ── Scopes ───────────────────────────────────────────────────────


    public function scopeActive($query)
    {
        return $query->where('statut', 'publiee');
    }


    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

 
    public function scopeRejetees($query)
    {
        return $query->where('statut', 'rejetee');
    }


    public function scopeSearch($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('titre', 'like', "%{$terme}%")
              ->orWhere('description', 'like', "%{$terme}%")
              ->orWhere('ville', 'like', "%{$terme}%");
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function prixFormate(): string
    {
        return $this->prix
            ? number_format($this->prix, 0, ',', ' ') . ' DH'
            : 'Prix à négocier';
    }

    public function imageUrl(): string
    {
        
        if ($this->relationLoaded('photos') && $this->photos->isNotEmpty()) {
            return asset('storage/' . $this->photos->first()->url);
        }
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder.jpg');
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'publiee'    => '✅ Publiée',
            'en_attente' => '⏳ En attente',
            'rejetee'    => '❌ Rejetée',
            default      => $this->statut,
        };
    }

    public function statutColor(): string
    {
        return match($this->statut) {
            'publiee'    => '#22c55e',
            'en_attente' => '#f59e0b',
            'rejetee'    => '#ef4444',
            default      => '#ffffff',
        };
    }
}