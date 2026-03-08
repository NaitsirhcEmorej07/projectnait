<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsystem extends Model
{
    protected $table = 'subsystem_tbl';

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'description',
        'route',
        'icon',
        'is_active',
    ];
}