<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeMateriel;

class TypeMaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(TypeMateriel::all()); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $type = TypeMateriel::create($request->validate([
            'nom' => 'required',
            'categorie_id' => 'required|exists:categories,id'
        ]));
        return response()->json($type, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(TypeMateriel::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $type = TypeMateriel::findOrFail($id);
        $type->update($request->validate([
            'nom' => 'required',
            'categorie_id' => 'required|exists:categories,id'
        ]));
        return response()->json($type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        TypeMateriel::destroy($id);
        return response()->json(null, 204);
    }
}
