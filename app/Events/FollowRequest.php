<?php

namespace App\Events;

use Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Notification;
use Log;


class FollowRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public $notification;

    public $isFollowing;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $notification_id)
    {
        Log::info('FollowRequest event fired');
        $this->user = User::find($user_id);
        $this->isFollowing = $this->user->isFollowing(Auth::id());
        $this->notification = Notification::find($notification_id);
        $this->message = " has sent you a follow request!";
    }
    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('FollowRequest event broadcasted');
        return ['FakeBook'];
    }

    public function broadcastAs(): string
    {
        Log::info('FollowRequest event broadcasted as notification-followrequest');
        return 'notification-followrequest';
    }
}
