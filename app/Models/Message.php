<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "incoming_msg_id",
        "outgoing_msg_id",
        "msg",
    ];
}
