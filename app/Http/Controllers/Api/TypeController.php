<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $type = Type::create($request->all());
        return response()->json($type, 201);
    }

    public function show(Type $type)
    {
        return response()->json($type);
    }

    public function update(Request $request, Type $type)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $type->update($request->all());
        return response()->json($type);
    }

    public function destroy(string $id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return response()->json(null, 204);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'type_ids' => 'required|array',
            'type_ids.*' => 'exists:types,id'
        ]);

        try {
            Type::whereIn('id', $request->type_ids)->delete();
            return response()->json(['message' => 'Types supprimés avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression des types'], 500);
        }
    }
}
