<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LeaveController extends Controller
{   

   public function __construct(
      private FinancialService $financialService,
   ) {}

   public function index(Request $request)
   {
      return view('leave.index');
   }

   public function edit(RequestStatus $requestStatus)
   {
      $getRsTransactionTypes = getRsTransactionTypes();
      $selectedTransactionTypeIds = $requestStatus->getSelectedTransactionTypeIds();
      $requestStatus->load(['chargings.allotment']);
      
      return view('financial.request_status.edit', compact(
         'requestStatus',
         'getRsTransactionTypes',
         'selectedTransactionTypeIds',
     ));
   }

   public function update(RequestStatusStoreUpdateRequest $request, RequestStatus $requestStatus)
   {
      $this->handleUpdate($request, $requestStatus);
      $requestStatus->refresh(); 
      return response()->json([
         'success' => true,
         'message' => $this->resolveResponseMessage($request),
         'rs_no'   => $requestStatus->fresh()->rs_no,
         'locked'  => $requestStatus->fresh()->locked_at ? true : false,
      ]);
   }
   
   public function show($id)
   {
      $requestStatus = RequestStatus::with(['division', 'payee', 'activities'])->findOrFail($id);
      $pdf = Pdf::loadView('financial.print.request_status', compact('requestStatus'));
      return $pdf->stream('modals.pdf');
   }

   public function destroy($id)
   {
      try {
         $this->financialService->deleteRequestStatus($id);
 
         return response()->json([
             'message' => 'Request status deleted successfully.'
         ], 200);
 
     } catch (\Exception $e) {
         return response()->json([
             'message' => $e->getMessage()
         ], 500);
     }
   }
}