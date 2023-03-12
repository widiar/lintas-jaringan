<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detail()
    {
        return $this->hasMany(DetailTicket::class, 'ticket_id', 'id');
    }
}
