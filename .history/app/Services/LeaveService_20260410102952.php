<?php

namespace App\Services\;

use Illuminate\Support\Facades\Auth;

class LeaveService {   
   public function createRequestStatus($request, Project $project): RequestStatus
   {
      $divisionId = $request['view_as_division_id']
      ?? session('view_as_division_id')
      ?? Auth::user()->division_id;
      $transactionTypeIds = $request['rs-transaction-type-id'] ?? [];
      $data = new RequestStatus([
         'project_id' => $project->id,
         'lib_id' => $request['lib-id'],
         'date' => $request['date'],
         'division_id' => $divisionId,
         'request_type_id' => 2,
         'rs_no' => $request['rs-no'] ?? null,
         'payee_id' => $request['payee-id'],
         'particulars' => $request['particulars'],
      ]);
      $data->save();
      $this->createOrUpdateRequestStatusTransactionType($transactionTypeIds, $data->id);
      return $data;
   }
}