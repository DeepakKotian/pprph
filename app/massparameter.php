<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class massparameter extends Model
{
    //
    protected $table="massparameter";
    protected $fillable = [
        'type','name',
    ];
}
