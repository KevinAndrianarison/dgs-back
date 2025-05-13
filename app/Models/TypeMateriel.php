<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMateriel extends Model
{
    use HasFactory;
    protected $table = 'types_materiels';
    protected $fillable = ['categorie_id', 'nom'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function materiels()
    {
        return $this->hasMany(Materiel::class, 'type_id');
    }
}
