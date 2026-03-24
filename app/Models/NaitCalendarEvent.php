<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NaitCalendarEvent extends Model
{
    protected $table = 'naitcalendar_events_tbl';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'type',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}