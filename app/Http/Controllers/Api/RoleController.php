<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Role::all(), 200);
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

        $role = Role::create($validated);
        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $author = Role::findOrFail($id);
        return response()->json($author, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update($validated);
        return response()->json($role, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }
    public function getRoleUsers($roleId)
    {
        $role = Role::with('admins')->findOrFail($roleId);
        return response()->json($role->admins);
    }
    public function attachAdmin($roleId, Request $request)
    {

        $role = Role::findOrFail($roleId);
        $request->validate(['admin_id' => 'required|exists:admins,id']);

        $role->admins()->attach($request->admin_id);

        return response()->json($role, 204);
    }
}
