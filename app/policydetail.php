<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class policydetail extends Model
{
    protected $table="policy_detail";
    protected $fillable = [
        'insurance_ctg_id','provider_id','document_name','policy_number','start_date','end_date','customer_id'
    ];
}
