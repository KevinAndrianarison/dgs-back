<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehiculeUtilisation;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;
use App\Models\Region;

class VehiculeUtilisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(VehiculeUtilisation::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $vehicule = VehiculeUtilisation::create($request->all());
        $historique = Historique::create([
            'titre' => 'Gestion de véhicule',
            'description' => 'Ajout de l\'utilisation du véhicule ' . $vehicule->materiel->type->nom . '-' . $vehicule->materiel->caracteristiques,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($vehicule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(VehiculeUtilisation::with('materiel')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $vehicule = VehiculeUtilisation::findOrFail($id);
        $vehicule->update($request->all());
        $historique = Historique::create([
            'titre' => 'Gestion de véhicule',
            'description' => 'Modification de l\'utilisation du véhicule ' . $vehicule->materiel->type->nom . '-' . $vehicule->materiel->caracteristiques,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($vehicule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $vehicule = VehiculeUtilisation::findOrFail($id);
        $vehicule->delete();
        $historique = Historique::create([
            'titre' => 'Gestion de véhicule',
            'description' => 'Suppression de l\'utilisation du véhicule ' . $vehicule->materiel->type->nom . '-' . $vehicule->materiel->caracteristiques,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json(null, 204);
    }
}
