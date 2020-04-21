<?php

namespace Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

use App\User;
use App\Models\Status;
use App\Events\ModelLiked;
use App\Notifications\NewLikeNotification;

class SendNewLikeNotificationTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function a_notification_is_sent_when_a_user_receives_a_new_like()
    {
    	Notification::fake();

    	$statusOwner = factory(User::class)->create();
    	$likeSender = factory(User::class)->create();

    	$status = factory(Status::class)->create(['user_id' => $statusOwner->id]);

    	$status->likes()->create([
    		'user_id' => $likeSender->id
    	]);

    	ModelLiked::dispatch($status, $likeSender);

    	Notification::assertSentTo(
    		$statusOwner, 
    		NewLikeNotification::class, 
    		function($notification, $channels) use ($likeSender, $status) {
				$this->assertContains('database', $channels);
				$this->assertContains('broadcast', $channels);
    			$this->assertTrue($notification->model->is($status));
    			$this->assertTrue($notification->likeSender->is($likeSender));

    			return true;
    		}
    	);
    }
}
