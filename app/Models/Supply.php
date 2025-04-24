<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','region_id', 'date', 'rubrique', 'stock_initial', 'entree',
        'sortie', 'stock_final', 'numero_be', 'lieu_destination',
        'transporteur', 'receptionnaire', 'observation'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
