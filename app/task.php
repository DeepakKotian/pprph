<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    //
    protected $fillable = [
        'task_name','task_detail','assigned_id','due_date','status','user_id'
    ];
}
