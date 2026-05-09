<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'slug', 'icone'];

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }
     public function create()
{
    $categories = Categorie::all();

    return view('annonces.create', compact('categories'));
}

public function edit(Annonce $annonce)
{
    $categories = Categorie::all();

    return view('annonces.edit', compact('annonce', 'categories'));
}
}