<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\User;

class CommentLikesController extends Controller
{
    public function store(Comment $comment) 
    {
        $comment->likes()->create([
            'user_id' => auth()->user()->id,
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->likes()->where([
            'user_id' => auth()->user()->id
        ])->delete();
    }
}
