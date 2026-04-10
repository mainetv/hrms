<?php

namespace App\Models;

use App\Models\Library\LibraryDivision;
use App\Models\Library\LibraryPayee;
use App\Models\Project\ProjectLIB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

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
