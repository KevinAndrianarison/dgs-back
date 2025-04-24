<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function types()
    {
        return $this->hasMany(TypeMateriel::class, 'categorie_id');
    }

    public function materiels()
    {
        return $this->hasMany(Materiel::class);
    }
}
