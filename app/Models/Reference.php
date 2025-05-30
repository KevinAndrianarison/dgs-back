<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function materiels()
    {
        return $this->hasMany(Materiel::class);
    }
}
