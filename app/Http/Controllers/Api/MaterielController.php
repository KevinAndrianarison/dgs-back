<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\TypeMateriel;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Materiel::with(['categorie', 'type', 'source', 'reference', 'region', 'responsable'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $type = TypeMateriel::find($request->type_id);
        $materiel = Materiel::create($request->all());
        return response()->json($materiel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Materiel::findOrFail($id));
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
            'source_id', 'reference_id'
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
