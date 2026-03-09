<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NaitNetworkPerson extends Model
{
    protected $table = 'naitnetwork_people_tbl';

    protected $fillable = [
        'user_id',
        'profile_picture',
        'name',
        'slug',
        'email',
        'phone',
        'summary',
        'notes',
        'is_public',
        'public_token',
        'public_expires_at',
        'show_email_publicly',
        'show_phone_publicly',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'show_email_publicly' => 'boolean',
        'show_phone_publicly' => 'boolean',
        'public_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            NaitNetworkRole::class,
            'naitnetwork_people_roles_tbl',
            'person_id',
            'role_id'
        )->withTimestamps();
    }

    public function socials(): HasMany
    {
        return $this->hasMany(
            NaitNetworkPeopleSocial::class,
            'person_id'
        );
    }

    public function isPubliclyViewable(): bool
    {
        if (!$this->is_public) {
            return false;
        }

        if (!$this->public_token) {
            return false;
        }

        if ($this->public_expires_at && now()->greaterThan($this->public_expires_at)) {
            return false;
        }

        return true;
    }
}