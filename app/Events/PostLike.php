<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class PostLike implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $post_id;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($post_id) {
        Log::info('PostLike event triggered');
        $this->post_id = $post_id;
        $this->notification = 'You like post ' . $post_id;
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        Log::info('PostLike event broadcastOn');
        return ['FakeBook'];
    }

    // You should specify the name of the generated notification.
    public function broadcastAs() {
        Log::info('PostLike event broadcastAs');
        return 'notification-postlike';
    }

}
