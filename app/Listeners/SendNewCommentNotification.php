<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentCreated;
use App\Notifications\NewCommentNotification;

class SendNewCommentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        $statusOwner = $event->comment->status->user;

        $statusOwner->notify(
            new NewCommentNotification($event->comment)
        );
    }
}
