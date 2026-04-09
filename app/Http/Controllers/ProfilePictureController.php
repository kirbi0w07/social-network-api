<?php

namespace App\Http\Controllers;

use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = $request->user();
        if ($request->hasFile('profile_picture')) {
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile = $request->user()->profile;

            $profile->profilePictures()->update(['is_current' => false]);
            
            $profilePicture = $profile->profilePictures()->create([
                'path' => $path,
                'is_current' => true,
            ]);

            $profile = $user->profile;

            return response()->json(['success' => true, 'message' => 'Profile picture uploaded successfully', 'profile' => $profile], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfilePicture $profilePicture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfilePicture $profilePicture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfilePicture $profilePicture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfilePicture $profilePicture)
    {
        //
    }
}
