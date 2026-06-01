<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['user_id',
    'comment',
    'commentable_i\d',
    'commentable_t\ype'])]
class Comment extends Model
{

public function user() {
        return $this->belongsTo(User::class);
}

public function commentable()
{
    return $this->morphTo();
}
}
