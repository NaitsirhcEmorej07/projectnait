<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaitNetworkSocialSelect extends Model
{
    protected $table = 'naitnetwork_socials_select_tbl';

    protected $fillable = [
        'name',
        'code',
        'icon',
        'is_active',
        'user_id',
    ];
}
