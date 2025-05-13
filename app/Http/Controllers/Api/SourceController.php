<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Source;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Source::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $source = Source::create($request->validate(['nom' => 'required|string|max:255']));
        return response()->json($source, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Source::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $source = Source::findOrFail($id);
        $source->update($request->validate(['nom' => 'required']));
        return response()->json($source);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $source = Source::findOrFail($id);
        $source->delete();
        return response()->json(null, 204);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'source_ids' => 'required|array',
            'source_ids.*' => 'exists:sources,id'
        ]);

        try {
            Source::whereIn('id', $request->source_ids)->delete();
            return response()->json(['message' => 'Sources supprimées avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression des sources'], 500);
        }
    }
}
