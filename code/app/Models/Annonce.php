<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';

    protected $fillable = [
        'id_utilisateur',
        'id_categorie',
        'titre',
        'description',
        'prix',
        'statut',
        'motif_rejet',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'date_publication' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'id_annonce')->orderBy('ordre');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'id_annonce');
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeActive($query)
    {
        return $query->where('statut', 'publiee');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // ========================
    // HELPERS
    // ========================

    public function prixFormate()
    {
        return $this->prix ? number_format($this->prix, 2, ',', ' ') . ' DH' : 'Prix sur demande';
    }
}
