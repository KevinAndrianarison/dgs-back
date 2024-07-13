<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::all();
        return response()->json($files, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    //
    {
        $validatedData = $request->validate([
            'titre' => 'required|string',
            'file' => 'required|file|mimes:jpg,png,pdf,docx'
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName(); // Récupère le nom original du fichier

        // Stocke le fichier dans le répertoire 'public/uploads' avec son nom d'origine
        $path = $file->storeAs('public/uploads', $fileName);

        // Crée l'enregistrement en base de données avec le nom du fichier d'origine
        $fileRecord = File::create([
            'titre' => $validatedData['titre'],
            'file_name' => $fileName
        ]);

        return response()->json($fileRecord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $file = File::find($id);

        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    //
    {
        $validatedData = $request->validate([
            'titre' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf,docx'
        ]);

        $fileRecord = File::findOrFail($id);

        if ($request->hasFile('file')) {
            // Supprime l'ancien fichier s'il existe
            if ($fileRecord->file_name) {
                Storage::delete('public/uploads/' . $fileRecord->file_name);
            }

            // Enregistre le nouveau fichier avec son nom d'origine
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/uploads', $fileName);

            $validatedData['file_name'] = $fileName;
        }

        $fileRecord->update($validatedData);

        return response()->json($fileRecord, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    //
    {
        $fileRecord = File::find($id);

        if ($fileRecord) {
            if ($fileRecord->file_name) {
                Storage::disk('public')->delete('uploads/' . $fileRecord->file_name);
            }

            $fileRecord->delete();
            return response()->json(['message' => 'File deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
