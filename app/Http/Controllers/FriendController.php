<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Notifications\FriendRequestNotification;
use Illuminate\Http\Request;


class FriendController extends Controller
{

private function getFriendshipButton(User $authUser, User $user): string
{
    $friendship = Friend::where(function ($query) use ($authUser, $user) {
        $query->where('sender_id', $authUser->id)
              ->where('receiver_id', $user->id);
    })
    ->orWhere(function ($query) use ($authUser, $user) {
        $query->where('sender_id', $user->id)
              ->where('receiver_id', $authUser->id);
    })
    ->first();

    if (!$friendship) {
        return 'add';
    }

    if ($friendship->status === 'accepted') {
        return 'friends';
    }

    if ($friendship->status === 'pending') {
        return $friendship->sender_id === $authUser->id
            ? 'pending'
            : 'accept';
    }

    return 'add';
}

public function addFriend(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $authUser = $request->user();
    $userId = $request->user_id;

    // Usuario que recibirá la solicitud
    $receiver = User::findOrFail($userId);

    // Evitar agregarse a sí mismo
    if ($authUser->id === $receiver->id) {
        return response()->json([
            'success' => false,
            'message' => 'You cannot add yourself.'
        ], 400);
    }
$authUser->load('profile.profilePictures');
    // Revisar si ya existe una solicitud
    $exists = Friend::where(function ($query) use ($authUser, $receiver) {
        $query->where('sender_id', $authUser->id)
              ->where('receiver_id', $receiver->id);
    })
    ->orWhere(function ($query) use ($authUser, $receiver) {
        $query->where('sender_id', $receiver->id)
              ->where('receiver_id', $authUser->id);
    })
    ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Friend request already exists.'
        ], 400);
    }

    $friendship = Friend::create([
        'sender_id' => $authUser->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending'
    ]);

    // Enviar la notificación al usuario receptor
    $receiver->notify(
        new FriendRequestNotification($authUser)
    );

    return response()->json([
        'success' => true,
        'friendship' => $friendship
    ]);
}

public function acceptFriendRequest(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $authUser = $request->user();
    $userId = $request->user_id;

    $friendship = Friend::where('sender_id', $userId)
        ->where('receiver_id', $authUser->id)
        ->firstOrFail();

    // Verificar que el usuario autenticado sea el receptor de la solicitud
    if ($friendship->receiver_id !== $authUser->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to accept this friend request.'
        ], 403);
    }

    // Actualizar el estado de la solicitud a "accepted"
    $friendship->status = 'accepted';
    $friendship->save();

    return response()->json([
        'success' => true,
        'friendship' => $friendship,
        'friendship_button' => $this->getFriendshipButton($authUser, User::find($userId))
    ]);
}

public function declineFriendRequest(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $authUser = $request->user();
    $userId = $request->user_id;

    $friendship = Friend::where('sender_id', $userId)
        ->where('receiver_id', $authUser->id)
        ->firstOrFail();

    // Verificar que el usuario autenticado sea el receptor de la solicitud
    if ($friendship->receiver_id !== $authUser->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to decline this friend request.'
        ], 403);
    }

    // Eliminar la solicitud de amistad
    $friendship->delete();

    return response()->json([
        'success' => true,
        'message' => 'Friend request declined.'
    ]);
}
}
