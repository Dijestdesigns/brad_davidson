<?php

namespace App\Events;

use App\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageIndividual implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new PrivateChannel('users.' . $this->chat->user_id . '.' . $this->chat->send_by);
    }

    public function broadcastWith()
    {
        $chat = Chat::select(Chat::getTableName() . '.*', Chat::getTableName() . '.id as chat_id')->where(Chat::getTableName() . '.id', $this->chat->id)->with('sentUser')->first();

        if (!empty($chat)) {
            $user = !empty($chat->sentUser) ? $chat->sentUser : [];

            $chat->user = $user;
        }

        return !empty($chat) ? $chat->toArray() : [];
    }
}
