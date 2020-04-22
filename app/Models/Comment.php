<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Like;
use App\Models\Status;
use App\Traits\HasLikes;

class Comment extends Model
{
    use HasLikes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTO(Status::class);
    }

    public function path()
    {
        return route('statuses.show', $this->status_id) . '#comment-' . $this->id;
    }

}
