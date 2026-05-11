<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';

    protected $fillable = [
        'id_annonce',
        'url',
        'nom_fichier',
        'ordre',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }
}
