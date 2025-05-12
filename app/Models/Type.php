<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description'
    ];

    public function materiels()
    {
        return $this->hasMany(Materiel::class);
    }
}
