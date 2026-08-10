<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'nullable|string|max:5000',
            'media' => 'nullable|array',
            'media.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/heic,image/heif,video/mp4,video/quicktime,video/x-msvideo,video/webm,video/mpeg,video/ogg,video/3gpp',
                'max:51200', // 50 MB por archivo
            ],
        ]);

        // Debe existir texto o al menos un archivo
        if (!$request->filled('body') && !$request->hasFile('media')) {
            return response()->json([
                'success' => false,
                'message' => 'La publicación debe contener texto o al menos un archivo.'
            ], 422);
        }

        $user = $request->user();

        $post = Post::create([
            'user_id' => $user->id,
            'body' => $request->body
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {

                $path = $file->store('posts');
                Log::info('Post media upload', [
                    'default_disk' => config('filesystems.default'),
                    'path' => $path,
                    'exists_default' => Storage::exists($path),
                    'exists_s3' => Storage::disk('s3')->exists($path),
                ]);
                $mime = $file->getMimeType();

                $type = str_starts_with($mime, 'video/')
                    ? 'video'
                    : 'image';

                $post->media()->create([
                    'file_path' => $path,
                    'type' => $type,
                    'order' => $index
                ]);
            }
        }

        $post->load('media', 'user.profile.profilePictures');
        $post->loadCount(['comments', 'reactions']);

        return response()->json([
            'success' => true,
            'message' => 'Post creado con éxito.',
            'post' => $post
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $postId)
    {
        //
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
    public function commentAPost(Request $request, Post $post)
    {
        $user = $request->user();
        $comment = $request->comment;
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'comment' => $comment
        ]);

        $comment->load('user.profile');
        return response()->json([
            'success' => true,
            'comment' => $comment,
            'comments_count' => $post->comments()->count()
        ], 200);
    }

    public function getCommentOfPost(Request $request, Post $post)
    {
        $user = $request->user();
        $comments = $post->comments()->with('user.profile')->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'comments_count' => $post->comments()->count()
        ], 200);
    }

    public function reactToComment(Request $request, Comment $comment)
    {
        $user = $request->user();
        $type = $request->type;

        // Buscamos si el usuario ya reaccionó a ESTE comentario específico
        $existingReaction = $comment->reactions()
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
            // Al usar $comment->reactions()->create, Laravel automáticamente asigna
            // el comment_id (o reactionable_id) y el string 'App\Models\Comment'
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => $type,
            ]);
            $userReaction = ['type' => $type];
        }

        return response()->json([
            'user_reaction' => $userReaction,
            'reactions_count' => $comment->reactions()->count()
        ], 200);
    }

    public function getPostsByUserId(Request $request)
    {
        $userId = $request->query('user_id');
        return response()->json([
            'success' => true,
            'posts' => Post::with('media', 'user')->where('user_id', $userId)->withCount(['comments', 'reactions'])->orderBy('created_at', 'desc')->get()
        ], 200);
    }
}
