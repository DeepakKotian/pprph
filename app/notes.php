<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notes extends Model
{
    // 
    protected $fillable = [
        'title','description','cust_id',
    ];
    
}
