<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reference;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Reference::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $reference = Reference::create($request->validate(['nom' => 'required|string|max:255']));
        return response()->json($reference, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Reference::findOrFail($id)); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $reference = Reference::findOrFail($id);
        $reference->update($request->validate(['nom' => 'required']));
        return response()->json($reference);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $reference = Reference::findOrFail($id);
        $reference->delete();
        return response()->json(null, 204);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'exists:references,id'
        ]);

        try {
            Reference::whereIn('id', $request->reference_ids)->delete();
            return response()->json(['message' => 'Références supprimées avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression des références'], 500);
        }
    }
}
