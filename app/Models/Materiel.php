<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero', 'numero_reference', 'caracteristiques', 'marque',
        'numero_serie', 'numero_imei', 'montant', 'date_acquisition',
        'date_transfert', 'lieu_affectation', 'etat', 'categorie_id',
        'type_id', 'source_id', 'reference_id', 'region_id', 'responsable_id','appartenance_id','datelimiteassurance','taux_amortissement','valeur_net'
    ];

    public function categorie() { return $this->belongsTo(Categorie::class); }
    public function type() { return $this->belongsTo(TypeMateriel::class, 'type_id'); }
    public function source() { return $this->belongsTo(Source::class); }
    public function reference() { return $this->belongsTo(Reference::class); }
    public function region() { return $this->belongsTo(Region::class); }
    public function responsable() { return $this->belongsTo(User::class, 'responsable_id'); }
    public function appartenance() { return $this->belongsTo(Appartenance::class, 'appartenance_id'); }
    public function photos() { return $this->hasMany(Photo::class); }

    public function utilisations()
    {
        return $this->hasMany(VehiculeUtilisation::class);
    }
}
