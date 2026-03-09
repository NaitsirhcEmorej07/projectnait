<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NaitNetworkPeopleSocial extends Model
{
    protected $table = 'naitnetwork_people_socials_tbl';

    protected $fillable = [
        'person_id',
        'platform',
        'url',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(NaitNetworkPerson::class, 'person_id');
    }
}