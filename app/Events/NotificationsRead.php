<?php

namespace App\Events;

use App\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;
use DB;

class NotificationsRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new PrivateChannel('dashboard-read-notifications');
    }

    public function broadcastWith()
    {
        $notifications = DB::select("SELECT n.*, u.profile_photo_icon FROM `notifications` AS n JOIN `users` AS u ON n.user_id = u.id WHERE n.`id` = '" . $this->id . "' LIMIT 1");

        if (!empty($notifications[0])) {
            $data = json_decode(json_encode($notifications[0]), true);

            $data['send_by_user']['profile_photo_icon'] = $data['profile_photo_icon'];

            return $data;
        }

        return [];
    }
}
