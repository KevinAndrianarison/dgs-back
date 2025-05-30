<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Historique;

class HistoriqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $historiques = Historique::all();
        return response()->json($historiques);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store()
    {
        //
        $historiques = Historique::all();
        return response()->json($historiques);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $historique = Historique::find($id);
        return response()->json($historique);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $historique = Historique::find($id);
        $historique->update($request->all());
        return response()->json($historique);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $historique = Historique::find($id);
        $historique->delete();
        return response()->json($historique);
    }
}
