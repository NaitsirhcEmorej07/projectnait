<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NaitNetworkRole extends Model
{
    protected $table = 'naitnetwork_roles_select_tbl';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(
            NaitNetworkPerson::class,
            'naitnetwork_people_roles_tbl',
            'role_id',
            'person_id'
        )->withTimestamps();
    }
}