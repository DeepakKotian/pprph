<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name','last_name','dob','email','email_office','last_name','telephone','company','gender','language','nationality','parent_id',
        'is_family', 'zip','city','country','address','mobile','unique_id',
    ];
}
