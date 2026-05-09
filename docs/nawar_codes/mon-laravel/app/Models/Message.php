<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'url',
        'nom_fichier',
        'ordre',
    ];

    // Relation : une photo appartient à une annonce
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    // Helper : URL complète
    public function urlComplete(): string
    {
        return asset('storage/' . $this->url);
    }
}