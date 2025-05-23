<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supply;

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
        //
        Supply::destroy($id); 
        return response()->json(null, 204);
    }
}
