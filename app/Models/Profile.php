<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
#[Fillable(['user_id', 'bio', 'gender', 'birthday', 'username'])]

class Profile extends Model
{
protected $appends = ['current_avatar'];
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
            get: fn () => $this->profilePictures->where('is_current', true)->first(),
        );
    }
}
