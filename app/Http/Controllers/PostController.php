<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'posts' => Post::with('media', 'user')->withCount(['comments', 'reactions'])->orderBy('created_at', 'desc')->get()
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'body'=> 'string|max:5000|required',
            'media' => 'array|min:1|max:5', // Valida el contenedor (el array)
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // Valida cada archivo individual
        ]);

        $user = $request->user();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => $request->body
        ]);

        if($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('posts', 'public');
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';

                $post->media()->create([
                    'file_path' => $path,
                    'type'      => $type,
                    'order'     => $index
                ]);
            }
        }
        $post->load('media');
        $post->loadCount(['comments', 'reactions']);
        return response()->json([
            'success' => true,
            'message' => 'post creado con exito',
            'post' => $post
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $postId)
    {
        return response()->json([
        'post' => Post::findById($postId)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function reactToPost(Request $request, Post $post)
{
    $user = $request->user();
    $type = $request->type;

    $existingReaction = $post->reactions()
        ->where('user_id', $user->id)
        ->first();

    if ($existingReaction) {
        if ($existingReaction->type === $type) {
            $existingReaction->delete();
            $userReaction = null;
        } else {
            $existingReaction->update(['type' => $type]);
            $userReaction = ['type' => $type];
        }
    } else {
        $post->reactions()->create([
            'user_id' => $user->id,
            'type' => $type,
        ]);
        $userReaction = ['type' => $type];
    }

    return response()->json([
        'user_reaction' => $userReaction,
        'reactions_count' => $post->reactions()->count()
    ], 200);
}
}
