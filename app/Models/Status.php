<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLikes;
use App\User;

class Status extends Model
{
    use HasLikes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
