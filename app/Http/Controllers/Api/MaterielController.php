<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\TypeMateriel;
use App\Models\Reference;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Materiel::with(['categorie', 'type', 'source', 'reference', 'region', 'responsable','appartenance'])->orderBy('date_acquisition', 'desc')->get());
    }

    public function getMaterielParIdRegion($regionId)
    {
    $materiels = Materiel::where('region_id', $regionId)
        ->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region'])
        ->orderBy('date_acquisition', 'desc')
        ->get();
    return response()->json($materiels);
    }


    public function getAllMaterielVehicule()
    {
        return response()->json(Materiel::whereHas('categorie', function($query) {
            $query->where('isVehicule', true);
        })->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region'])->orderBy('date_acquisition', 'desc')->get());
    }

    public function getMaterielVehiculeParIdRegion($regionId)
    {
        return response()->json(Materiel::whereHas('categorie', function($query) {
            $query->where('isVehicule', true);
        })->where('region_id', $regionId)->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region'])->orderBy('date_acquisition', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $reference = Reference::findOrFail($request->reference_id);
        $count = Materiel::where('reference_id', $request->reference_id)->count();
        $numero = $reference->nom . '-' . ($count + 1);
        
        $data = $request->all();
        $data['numero'] = $numero;
        
        $materiel = Materiel::create($data);
        return response()->json($materiel, 201);
    }

    public function changeIdRegion(Request $request, string $idMateriel, string $idRegion)
    {
        $materiel = Materiel::findOrFail($idMateriel);
        $materiel->region_id = $idRegion;
        if ($request->responsable_id) {
            $materiel->responsable_id = $request->responsable_id;
        }
        $materiel->save();
        return response()->json($materiel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Materiel::with(['categorie', 'type', 'source', 'reference', 'region', 'responsable','appartenance'])->orderBy('date_acquisition', 'desc')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $materiel = Materiel::findOrFail($id);
        
        // Récupérer uniquement les champs qui sont présents dans la requête
        $validFields = $request->only([
            'numero', 'categorie_id', 'type_id', 'marque', 'caracteristiques', 
            'etat', 'montant', 'numero_serie', 'numero_imei', 'region_id', 
            'responsable_id', 'date_acquisition', 'lieu_affectation', 
            'source_id', 'reference_id', 'appartenance_id'
        ]);

        // Ne mettre à jour que les champs présents
        $materiel->update($validFields);

        return response()->json($materiel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Materiel::destroy($id); 
        return response()->json(null, 204);
    }
}
