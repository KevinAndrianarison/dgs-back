<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Admin::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $admin = Admin::create($validated);
        return response()->json($admin, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $author = Admin::findOrFail($id);
        return response()->json($author, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $admin->update($validated);
        return response()->json($admin, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        {
            $author = Admin::findOrFail($id);
            $author->delete();
            return response()->json(null, 204);
        }
    }

    public function attachRole($adminId, Request $request)
    {
        $admin = Admin::findOrFail($adminId);
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $admin->roles()->attach($request->role_id);
        return response()->json($admin, 204);
    }

    public function detachRole($adminId, $roleId)
    {
        $admin = Admin::findOrFail($adminId);
        $admin->roles()->detach($roleId);
        return response()->json(null, 204);
    }
    public function getUserRoles($adminId)
    {
        $admin = Admin::with('roles')->findOrFail($adminId);
        return response()->json($admin->roles);
    }
}
