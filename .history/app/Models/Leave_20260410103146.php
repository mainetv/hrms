<?php

namespace App\Models;

class Leave extends Model
{

    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'request_type_id',
        'reason',
        'status',
    ];
    

}
