<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaitNote extends Model
{
    protected $table = 'naitnotes_tbl';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'color',
    ];
}
