<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

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
            'gender' => 'required|in:male,female,other,i\'d_prefer_not_to_say',
            'birthday' => 'date|before:today',
        ]);

        $user = User::create($validated);
        
        $user->profile()->create([
            'username' => $request->username,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['success' => true, 'message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
    }
}
