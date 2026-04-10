<?php

namespace App\Services;

use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveService {   
   public function createLeave($request)
   {
      $transactionTypeIds = $request['rs-transaction-type-id'] ?? [];
      $data = new Leave([
         'from_date' => $request['user_id'] ?? null,
         'from_date' => $request['from_date'] ?? null,
         'to_date' => $request['to_date'] ?? null,
         'request_type_id' => $request['request_type_id'] ?? null,
         'reason' => $request['reason'] ?? null,
         'status' => $request['status'] ?? null,
      ]);
      $data->save();
      $this->createOrUpdateRequestStatusTransactionType($transactionTypeIds, $data->id);
      return $data;
   }
}