<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'nullable',
            'password' => 'nullable',
            'role' => 'required',
            'numeros' => 'nullable',
            'region_id' => 'nullable|exists:regions,id',
        ]);
    
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'role' => $validatedData['role'],
            'numeros' => $validatedData['numeros'] ?? null,
            'region_id' => $validatedData['region_id'] ?? null,
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : null,
        ]);
            
        if (!empty($user->email) && !empty($validatedData['password'])) {
    
            $messageContent = "Bonjour,\n\n" .
                "Votre compte a été créé avec succès !\n" .
                "Voici votre mot de passe : {$validatedData['password']}\n" .
                "Vous pouvez le changer à tout moment depuis votre espace personnel.\n\n" .
                "Cordialement.";
    
            Mail::raw($messageContent, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject("Création de votre compte");
            });
        }
    
        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($validatedData)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
    public function profil()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}
