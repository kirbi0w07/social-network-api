<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
       public function search(Request $request)
{
    $request->validate([
        'search' => 'required|string'
    ]);

    $search = $request->search;
    $authUser = $request->user();

    $users = User::with('profile')
        ->where(function ($query) use ($search) {

            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$search}%"])
                  ->orWhereHas('profile', function ($q) use ($search) {
                      $q->where('username', 'LIKE', "%{$search}%");
                  });

        })
        ->get()
        ->map(function ($user) use ($authUser) {

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

                $user->friendship_status = null;

            } elseif ($friendship->status === 'accepted') {

                $user->friendship_status = 'accepted';

            } elseif ($friendship->sender_id === $authUser->id) {

                $user->friendship_status = 'pending_sent';

            } else {

                $user->friendship_status = 'pending_received';

            }

            return $user;
        });

    return response()->json([
        'success' => true,
        'users' => $users
    ]);
}
}
