<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{   

   public function __construct(
      private LeaveService $leaveService,
   ) {}

   public function index(Request $request)
   {
      $getLeaveTypes = LeaveType::all();
      return view('leave.index');
   }

   public function edit(Leave $request)
   {
      $getLeaveTypes = LeaveType::all();
      $getLeaveData = Leave::all();
      
      return view('leave.edit', compact(
         'getLeaveTypes',
         'getLeaveData',
     ));
   }

   public function update(Leave $request)
   {
     
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