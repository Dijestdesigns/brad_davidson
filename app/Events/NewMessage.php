<?php

namespace App\Events;

use App\Chat;
use App\ChatRoomUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
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
        return new PrivateChannel('rooms.' . $this->chat->chatRoomUser->chatRoom->id);
    }

    public function broadcastWith()
    {
        $chat = Chat::select(Chat::getTableName() . '.id as chat_id', Chat::getTableName() . '.message', Chat::getTableName() . '.file', Chat::getTableName() . '.created_at', ChatRoomUser::getTableName() . '.*')
                    ->where(Chat::getTableName() . '.id', $this->chat->id)
                    ->join(ChatRoomUser::getTableName(), Chat::getTableName() . '.chat_room_user_id', '=', ChatRoomUser::getTableName() . '.id')
                    ->with('user')->first();

        return !empty($chat) ? $chat->toArray() : [];
        /*return [
            'message' => $this->chat->message,
            'user' => [
                'id' => $this->chat->chatRoomUser->user->id,
                'name' => $this->chat->chatRoomUser->user->fullname,
            ]
        ];*/
    }
}
