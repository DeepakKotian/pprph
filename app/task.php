<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    //
    protected $fillable = [
        'task_name','task_detail','type','assigned_id','start_date','due_date','status','user_id'
    ];
}
