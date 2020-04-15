<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\User;

class CommentLikesController extends Controller
{
    public function store(Comment $comment) 
    {
        $comment->like();
    }

    public function destroy(Comment $comment)
    {
        $comment->unlike();
    }
}
