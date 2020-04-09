<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Like;
use App\Traits\HasLikes;

class Comment extends Model
{
    use HasLikes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
