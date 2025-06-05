<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'materiel_id',
        'chemin',
    ];
    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }
}
