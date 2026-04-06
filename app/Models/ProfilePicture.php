<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
#[Fillable(['user_id', 'path', 'is_current'])]
class ProfilePicture extends Model
{
    protected $appends = ['full_url'];
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    protected function fullUrl(): Attribute
    {
        return new Attribute(
            get: fn() => $this->path ? asset('storage/' . $this->path) : null,
        );
    }
}
