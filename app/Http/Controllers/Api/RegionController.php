<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Region::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si une région avec le même nom existe déjà
        $existingRegion = Region::where('nom', $request->input('nom'))->first();
        if ($existingRegion) {
            return response()->json([
                'message' => 'Une région avec ce nom existe déjà.'
            ], 409);
        }

        $region = Region::create($request->validate([ 'nom' => 'required' ]));
        return response()->json($region, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Region::findOrFail($id)); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $region = Region::findOrFail($id);
        
        // Vérifier si une autre région avec le même nom existe déjà
        $existingRegion = Region::where('nom', $request->input('nom'))
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingRegion) {
            return response()->json([
                'message' => 'Une région avec ce nom existe déjà.'
            ], 409);
        }

        $region->update($request->validate([ 'nom' => 'required' ]));
        return response()->json($region);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Region::destroy($id);
        return response()->json(null, 204);
    }

    /**
     * Supprimer plusieurs régions en une seule fois
     */
    public function destroyMultiple(Request $request)
    {
        // Valider que la requête contient bien un tableau d'IDs
        $validatedData = $request->validate([
            'region_ids' => 'required|array',
            'region_ids.*' => 'exists:regions,id'
        ]);

        // Récupérer les IDs à supprimer
        $regionIds = $validatedData['region_ids'];

        // Supprimer les régions
        $deletedCount = Region::whereIn('id', $regionIds)->delete();

        // Retourner une réponse avec le nombre de régions supprimées
        return response()->json([
            'message' => 'Régions supprimées avec succès',
            'deleted_count' => $deletedCount
        ], 200);
    }
}
