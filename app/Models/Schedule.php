<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'sched_date',
        'sched_slot',
        'is_delete',
    ];

    public function scheduleUser()
    {
        return $this->hasMany(ScheduledUser::class);
    }
}
