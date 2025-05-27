<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehiculeUtilisation;

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
        return response()->json($vehicule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        VehiculeUtilisation::destroy($id); 
        return response()->json(null, 204);
    }
}
