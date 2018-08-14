<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class insurancemapped extends Model
{
    //
    protected $table="insurance_mapped";
    protected $fillable = [
        'insurance_ctg_id','provider_id','document_name',
    ];
}
