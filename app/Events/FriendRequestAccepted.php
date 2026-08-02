<?php

namespace App\Events;

use App\Models\Friend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\SerializesModels;

class FriendRequestAccepted implements ShouldBroadcastNow
{
        use Dispatchable, SerializesModels;

    public function __construct(
        public Friend $friendship,
        public DatabaseNotification $notification
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'notifications.' . $this->friendship->sender_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'friend.request.accepted';
    }

    public function broadcastWith(): array
{
    return [
            'friendship' => [
                'id' => $this->friendship->id,
                'sender_id' => $this->friendship->sender_id,
                'receiver_id' => $this->friendship->receiver_id,
                'status' => $this->friendship->status,
            ],

            'notification' => [
                'id' => $this->notification->id,
                'type' => $this->notification->type,
                'notifiable_type' => $this->notification->notifiable_type,
                'notifiable_id' => $this->notification->notifiable_id,
                'data' => $this->notification->data,
                'read_at' => $this->notification->read_at,
                'created_at' => $this->notification->created_at,
                'updated_at' => $this->notification->updated_at,
            ],
        ];
}
}
