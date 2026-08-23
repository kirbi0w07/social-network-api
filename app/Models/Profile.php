<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'bio', 'gender', 'birthday', 'username', 'cover_picture'])]

class Profile extends Model
{
    protected $appends = ['current_avatar', 'cover_picture_url'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profilePictures()
    {
        return $this->hasMany(ProfilePicture::class);
    }

    protected function currentAvatar(): Attribute
    {
        return new Attribute(
            get: fn() => $this->profilePictures->where('is_current', true)->first(),
        );
    }

    protected function coverPictureUrl(): Attribute
    {
        return new Attribute(
            get: fn() => $this->cover_picture
                ? Storage::url($this->cover_picture)
                : null,
        );
    }
}
