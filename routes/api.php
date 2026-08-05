<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\SearchController;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

Route::get('/me', function (Request $request) {
    return $request->user();
}
)->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('profile', ProfileController::class);
    Route::resource('profile_pictures', ProfilePictureController::class);
    Route::post('cover_picture', [ProfilePictureController::class, 'uploadCover']);
    Route::resource('post', PostController::class);
    Route::get('get_posts_by_user_id', [PostController::class, 'getPostsByUserId']);
    Route::post('/post/{post}/react', [PostController::class, 'reactToPost']);
    Route::post('/post/{post}/reactToComment', [PostController::class, 'reactToComment']);
    Route::post('/post/{post}/comment', [PostController::class, 'commentAPost']);
    Route::post('/post/{post}/get_comment', [PostController::class, 'getCommentOfPost']);
    Route::post('/search', [SearchController::class, 'search']);

    Route::post('/add_friend', [FriendController::class, 'addFriend']);
    Route::post('/accept_friend_request', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/decline_friend_request', [FriendController::class, 'declineFriendRequest']);

    Route::get('/get_user_by_username', [ProfileController::class, 'getUserByUsername']);

    Route::get('/notifications', [NotificationController::class, 'index']);

});
