<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsSupply extends Model
{
    use HasFactory;
    protected $fillable = [
        'supply_id',
        'rubrique',
        'entree',
        'sortie',
        'numero_be',
        'lieu_destination',
        'transporteur',
        'receptionnaire',
        'observation',
        'date'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
