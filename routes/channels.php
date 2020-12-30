<?php

use Illuminate\Support\Facades\Broadcast;
use App\ChatRoom;
use App\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{id}.{senderId}', function ($user, $id, $senderId) {
    // return (int) $user->id === (int) $id;
    return User::where('id', $id)->exists();
});

Broadcast::channel('rooms.{room}', function ($user, $chatRoomId) {
    $chatRoom = ChatRoom::find($chatRoomId);

    if ($chatRoom) {
        return $chatRoom->hasUser($user->id);
    }

    return false;
    // return $chat->hasUser($user->id);
});

Broadcast::channel('dashboard-notifications', function ($user) {
    return $user;
    // return User::where('id', $id)->first();
    // return ['id' => $id, 'senderId' => $senderId];
});

Broadcast::channel('dashboard-read-notifications', function ($user) {
    return $user;
});

Broadcast::channel('users', function ($user) {
    return $user;
    // return User::where('id', $id)->first();
    // return ['id' => $id, 'senderId' => $senderId];
});
