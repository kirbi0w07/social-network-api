<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Profile;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        return response()->json([
            'success' => true,
            'profile' => $profile,
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }

public function getUserByUsername(Request $request)
{
    $username = $request->query('username');

    $authUser = $request->user();

    $userFinded = User::with('profile')
        ->whereHas('profile', function ($query) use ($username) {
            $query->where('username', $username);
        })
        ->first();

    if (!$userFinded) {
        return response()->json([
            'success' => false
        ], 404);
    }

    $friendship = Friend::where(function ($query) use ($authUser, $userFinded) {
            $query->where('sender_id', $authUser->id)
                  ->where('receiver_id', $userFinded->id);
        })
        ->orWhere(function ($query) use ($authUser, $userFinded) {
            $query->where('sender_id', $userFinded->id)
                  ->where('receiver_id', $authUser->id);
        })
        ->first();

    // Valor por defecto
    $userFinded->friendship_button = 'add';

    if ($friendship) {

        if ($friendship->status === 'accepted') {
            $userFinded->friendship_button = 'friends';
        }

        elseif (
            $friendship->status === 'pending' &&
            $friendship->sender_id === $authUser->id
        ) {
            $userFinded->friendship_button = 'pending';
        }

        elseif (
            $friendship->status === 'pending' &&
            $friendship->receiver_id === $authUser->id
        ) {
            $userFinded->friendship_button = 'accept';
        }
    }

    return response()->json([
        'success' => true,
        'userFinded' => $userFinded
    ]);
}
}
