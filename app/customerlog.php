<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customerlog extends Model
{
    protected $fillable = [
        'user_id','customer_id','logs','type',
    ];
}
