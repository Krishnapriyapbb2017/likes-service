<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
//    public $timestamps = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'created_at'
    ];
}
