<?php

use App\Models\Ticket;
use Vinkla\Hashids\Facades\Hashids;

function getTicketAdmin()
{
    $data = Ticket::with('detail')->where('user_admin', null)->get();
    return $data;
}

function encodehasids($id)
{
    return Hashids::encode($id);
}
