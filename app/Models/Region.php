<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function materiels()
    {
        return $this->hasMany(Materiel::class);
    }

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }
}
