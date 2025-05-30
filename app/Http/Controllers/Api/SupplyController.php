<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\Region;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Supply::with(['region', 'detailsSupply'=> function($query){
            $query->orderBy('date', 'desc');
        }])->orderBy('date', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $supply = Supply::create($request->all());
        $nomRegion = Region::findOrFail($request->region_id)->nom;
        $historique = Historique::create([
            'titre' => 'Approvisionnement',
            'description' => 'Ajout des stocks ' . $request->nom . ' dans la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($supply, 201);
    }

    public function shareSupplyToRegion(Request $request, string $idRegion, string $idSupply)
    {
        //
        $supply = Supply::findOrFail($idSupply);
        $supply->region_id = $idRegion;
        if ($request->receptionnaire) {
            $supply->receptionnaire = $request->receptionnaire;
        }
        $supply->save();
        $nomRegion = Region::findOrFail($idRegion)->nom;
        $historique = Historique::create([
            'titre' => 'Approvisionnement',
            'description' => 'Transfert des stocks ' . $supply->nom . ' vers la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($supply);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Supply::with(['region', 'detailsSupply'=> function($query){
            $query->orderBy('date', 'desc');
        }])->orderBy('date', 'desc')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $supply = Supply::findOrFail($id);
        $supply->update($request->all());
        $nomRegion = Region::findOrFail($supply->region_id)->nom;
        $historique = Historique::create([
            'titre' => 'Approvisionnement',
            'description' => 'Modification des stocks ' . $supply->nom . ' dans la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($supply);
    }

    public function getByIdRegion(string $regionId)
    {
        //
        return response()->json(Supply::with(['region', 'detailsSupply'=> function($query){
            $query->orderBy('date', 'desc');
        }])->orderBy('date', 'desc')->where('region_id', $regionId)->get());
    }

    public function addOrMinusSupply(Request $request, string $id)
    {
        //
        $supply = Supply::findOrFail($id);
        if($request->isMinus){
            if($supply->stock_final < $request->stock_final){
                return response()->json(['message' => 'Stock final ne peut pas être négatif'], 400);
            }
            $supply->stock_final -= $request->stock_final;
        }else{
            $supply->stock_final += $request->stock_final;
        }
        $supply->save();
        return response()->json($supply);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supply = Supply::findOrFail($id);
        // Delete all related details_supplies records first
        $supply->detailsSupply()->delete();
        // Then delete the supply
        $supply->delete();
        $nomRegion = Region::findOrFail($supply->region_id)->nom;
        $historique = Historique::create([
            'titre' => 'Approvisionnement',
            'description' => 'Suppression des stocks ' . $supply->nom . ' dans la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json(null, 204);
    }
}
