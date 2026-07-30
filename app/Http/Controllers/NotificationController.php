<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {
        return response()->json([
            'success' => true,
            'notifications' => $request->user()
                ->notifications()
                ->latest()
                ->get()
        ]);
    }
}
