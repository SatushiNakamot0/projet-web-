<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'id_expediteur',
        'id_destinataire',
        'id_annonce',
        'objet',
        'contenu',
        'lu',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    // ========================
    // RELATIONS
    // ========================

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'id_expediteur');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'id_destinataire');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }
}
