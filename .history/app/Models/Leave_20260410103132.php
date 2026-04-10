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
    

    public function lib(): BelongsTo
    {
        return $this->BelongsTo(ProjectLIB::class, 'lib_id');
    }

    public function division(): BelongsTo
    {
        return $this->BelongsTo(LibraryDivision::class, 'division_id');
    }

    public function payee(): BelongsTo
    {
        return $this->BelongsTo(LibraryPayee::class, 'payee_id');
    }

    public function transactionTypes(): HasMany
    {
        return $this->hasMany(RequestStatusTransactionType::class, 'rs_id', 'id');
    }

    public function getSelectedTransactionTypeIds(): array
    {
       return $this->transactionTypes->pluck('rs_transaction_type_id')->toArray();
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'rs_transaction_type_id');
    }

    public function chargings()
    {
        return $this->hasMany(RequestStatusCharging::class, 'rs_id');
    }

    public function allotments()
    {
        return $this->hasMany(RequestStatusCharging::class, 'rs_id');
    }

    public function getSignatoriesAttribute()
    {
        return collect([
            ['name' => $this->signatory1, 'position' => $this->signatory1_position],
            ['name' => $this->signatory1b, 'position' => $this->signatory1b_position],
            ['name' => $this->signatory2, 'position' => $this->signatory2_position],
            ['name' => $this->signatory3, 'position' => $this->signatory3_position],
            ['name' => $this->signatory4, 'position' => $this->signatory4_position],
            ['name' => $this->signatory5, 'position' => $this->signatory5_position],
            ['name' => $this->signatory6b, 'position' => $this->signatory6b_position],
            ['name' => $this->signatory7, 'position' => $this->signatory7_position],
            ['name' => $this->signatory8, 'position' => $this->signatory8_position],
            ['name' => $this->signatory9, 'position' => $this->signatory9_position],
            ['name' => $this->signatory10, 'position' => $this->signatory10_position],
        ])->filter(fn($s) => !empty($s['name'])); // remove empty signatories
    }


}
