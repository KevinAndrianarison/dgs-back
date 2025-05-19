<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appartenance;

class AppartenanceController extends Controller
{
    public function index()
    {
        return response()->json(Appartenance::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required',
        ]);

        $appartenance = Appartenance::create($validatedData);
        return response()->json($appartenance, 201);
    }

    public function show($id)
    {
        $appartenance = Appartenance::findOrFail($id);
        return response()->json($appartenance);
    }

    public function update(Request $request, $id)
    {
        $appartenance = Appartenance::findOrFail($id);
        
        $validatedData = $request->validate([
            'nom' => 'required',
        ]);

        $appartenance->update($validatedData);
        return response()->json($appartenance);
    }

    public function destroy($id)
    {
        $appartenance = Appartenance::findOrFail($id);
        $appartenance->delete();
        return response()->noContent();
    }

    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'appartenance_ids' => 'required|array',
            'appartenance_ids.*' => 'exists:appartenances,id'
        ]);

        try {
            Appartenance::whereIn('id', $request->appartenance_ids)->delete();
            return response()->json(['message' => 'Appartenances supprimées avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression des appartenances'], 500);
        }
    }
}
