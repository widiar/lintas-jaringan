<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Broadcast;
use Vinkla\Hashids\Facades\Hashids;

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

Broadcast::channel('chatPosted.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    // $idDecode = Hashids::decode($id);
    // $ticket = Ticket::where('id', $idDecode)->first();
    // if (($user->id == $ticket->user_id || $user->id == $ticket->user_admin)) return true;
    // return false;
    return true;
});
