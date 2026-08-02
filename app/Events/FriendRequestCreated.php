<?php

namespace App\Events;

use App\Models\Friend;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\SerializesModels;

class FriendRequestCreated implements ShouldBroadcastNow
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
                'notifications.' . $this->friendship->receiver_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'friend.request.created';
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
                'message' => $this->notification->data['message'],
                'data' => $this->notification->data,
                'read_at' => $this->notification->read_at,
                'created_at' => $this->notification->created_at,
                'updated_at' => $this->notification->updated_at,
            ],
        ];
}
}
