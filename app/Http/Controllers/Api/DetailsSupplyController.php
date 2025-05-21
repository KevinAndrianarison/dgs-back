<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DetailsSupply;
use Illuminate\Http\Request;
use App\Models\Supply;

class DetailsSupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detailsSupplies = DetailsSupply::with('supply')->get();
        return response()->json($detailsSupplies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supply_id' => 'required',
            'rubrique' => 'required',
            'entree' => 'nullable',
            'sortie' => 'nullable',
            'numero_be' => 'nullable',
            'lieu_destination' => 'nullable',
            'transporteur' => 'nullable',
            'receptionnaire' => 'nullable',
            'observation' => 'nullable',
            'date' => 'required',
        ]);

        $detailsSupply = DetailsSupply::create($request->all());
        $supply = Supply::find($request->supply_id);
        if ($request->entree) {
            $supply->stock_final += $request->entree;
        }
        if ($request->sortie) {
            if ($supply->stock_final < $request->sortie) {
                return response()->json(['message' => 'Stock final ne peut pas être négatif'], 400);
            }
            $supply->stock_final -= $request->sortie;
        }
        $supply->save();
        return response()->json($detailsSupply, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(DetailsSupply::with('supply')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supply_id' => 'required',
            'rubrique' => 'nullable',
            'entree' => 'nullable',
            'sortie' => 'nullable',
            'numero_be' => 'nullable',
            'lieu_destination' => 'nullable',
            'transporteur' => 'nullable',
            'receptionnaire' => 'nullable',
            'observation' => 'nullable',
            'date' => 'nullable',
        ]);

        $detailsSupply = DetailsSupply::findOrFail($id);
        $detailsSupply->update($request->all());
        $supply = Supply::find($request->supply_id);
        if ($request->entree) {
            $supply->stock_final += $request->entree;
        }
        if ($request->sortie) {
            if ($supply->stock_final < $request->sortie) {
                return response()->json(['message' => 'Stock final ne peut pas être négatif'], 400);
            }
            $supply->stock_final -= $request->sortie;
        }
        $supply->save();
        return response()->json($detailsSupply);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detailsSupply = DetailsSupply::findOrFail($id);
        $detailsSupply->delete();
        $supply = Supply::find($detailsSupply->supply_id);
        if ($detailsSupply->entree) {
            $supply->stock_final -= $detailsSupply->entree;
        }
        if ($detailsSupply->sortie) {
            $supply->stock_final += $detailsSupply->sortie;
        }
        $supply->save();
        return response()->json(null, 204);
    }
}
