<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Categorie::all()); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $categorie = Categorie::create($request->validate([ 'nom' => 'required' ]));
        if ($request->isVehicule == true) {
            $categorie->isVehicule = true;
            $categorie->save();
        }
        return response()->json($categorie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Categorie::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $categorie = Categorie::findOrFail($id);
        $categorie->update($request->validate([ 'nom' => 'required' ]));
        return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return response()->json(null, 204);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        try {
            Categorie::whereIn('id', $request->category_ids)->delete();
            return response()->json(['message' => 'Catégories supprimées avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression des catégories'], 500);
        }
    }
}
