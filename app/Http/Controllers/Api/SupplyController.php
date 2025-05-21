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
        return response()->json(Supply::with('region')->get());
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Supply::with('region')->findOrFail($id));
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
        return response()->json(Supply::with('region')->where('region_id', $regionId)->get());
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
