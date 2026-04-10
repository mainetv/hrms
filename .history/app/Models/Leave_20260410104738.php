<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'leave-type-id',
        'reason',
        'status',
    ];
    

}
