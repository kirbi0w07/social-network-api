<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:profiles',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'birthday' => 'date|before:today',
        ]);
        DB::beginTransaction();
        try {
            $user = User::create($validated);
            $user->profile()->create([
                'username' => $request->username,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
            ]);
        DB::commit();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['success' => true, 'message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
        } catch (\Throwable $th) {
            return response()->json([
            'success' => false,
            'message' => 'Error al registrar: ' . $th->getMessage()
        ], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        //desencriptar la contraseña y verificar que coincida con la del usuario
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['success' => true, 'message' => 'User logged in successfully', 'user' => $user, 'token' => $token], 200);
    }

    public function logout( Request $request) {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No hay sesión activa'], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Token eliminado correctamente']);
    }
}
