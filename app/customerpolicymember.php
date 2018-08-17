<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customerpolicymember extends Model
{
     //
     protected $table="customer_policy_member";
     protected $fillable = [
         'family_member_id','policy_detail_id',
     ];
}
