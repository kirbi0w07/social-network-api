<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PostMedia extends Model
{
    protected $fillable = ['post_id', 'file_path', 'type', 'order'];
    protected $appends = ['file_url'];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    protected function fileUrl(): Attribute
{
    return Attribute::make(
        get: fn () => $this->file_path ? asset(Storage::url($this->file_path)) : null,
    );
}
}
