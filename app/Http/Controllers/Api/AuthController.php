<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
    public function destroy(string $idUser)
    {
        $user = User::find($idUser);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Supprimer la photo si elle existe
        if ($user->photo_url) {
            Storage::delete('public/' . $user->photo_url);
        }

        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        // Gérer l'upload de la photo si elle existe
        $photoUrl = null;
        
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/photos/profil', $filename);
            $photoUrl = 'photos/profil/' . $filename;
        }
    
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'role' => $validatedData['role'],
            'numeros' => $validatedData['numeros'] ?? null,
            'region_id' => $validatedData['region_id'] ?? null,
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : null,
            'photo_url' => $photoUrl,
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

        return $this->createNewToken($token, Auth::user()->region_id);
    }

    public function createNewToken($token, $regionId)
    {
        $region = Region::find($regionId);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => Auth::user(),
            'region' => $region,
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

    public function getUser()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'region' => $user->region // Accède directement à la région
        ]);
    }
    

    public function updateUser(Request $request)
    {
        $user = auth()->user();
        $field = $request->input('field');
        $value = $request->input('value');
        
        $rules = [
            'name' => 'required',
            'email' => 'required' . $user->id,
            'numeros' => 'nullable'
        ];

        if (!array_key_exists($field, $rules)) {
            return response()->json([
                'message' => 'Champ invalide'
            ], 422);
        }

        $validator = validator([$field => $value], [
            $field => $rules[$field]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user->update([$field => $value]);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|different:current_password',
            'confirm_password' => 'required|same:new_password'
        ]);

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect'
            ], 422);
        }

        $user->password = bcrypt($validatedData['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $user = auth()->user();
        
        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        // Supprimer l'ancienne photo si elle existe
        if ($user->photo_url) {
            Storage::delete('public/' . $user->photo_url);
        }

        // Upload de la nouvelle photo
        $photo = $request->file('photo');
        $filename = time() . '_' . $photo->getClientOriginalName();
        $photo->storeAs('public/photos/profil', $filename);
        
        // Mettre à jour l'URL de la photo
        $user->photo_url = 'photos/profil/' . $filename;
        $user->save();

        return response()->json([
            'message' => 'Photo de profil mise à jour avec succès',
            'photo_url' => $user->photo_url
        ]);
    }
}
