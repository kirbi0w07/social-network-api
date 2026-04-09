<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePictureController;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
}
)->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('profile', Profile::class);
    Route::resource('profile_pictures', ProfilePictureController::class);
});
