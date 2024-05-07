<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'scheduled_at',
        'paid_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
