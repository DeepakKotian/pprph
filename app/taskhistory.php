<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class taskhistory extends Model
{
    //
    protected $fillable = [
        'task_name','task_id','task_detail','type','assigned_id','start_date','due_date','status','user_id','comment',
    ];
}
