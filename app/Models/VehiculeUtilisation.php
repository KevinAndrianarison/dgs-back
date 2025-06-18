<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculeUtilisation extends Model
{
    use HasFactory;
    protected $fillable = [
        'materiel_id', 'date', 'chef_missionnaire', 'lieu', 'activite',
        'carburant', 'immatriculation', 'km_depart', 'km_arrivee',
        'total_km', 'qtt_litre', 'pu_ariary', 'montant', 'isplannification'
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }
}
