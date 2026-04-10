<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{   

   public function __construct(
      private LeaveService $leaveService,
   ) {}

   public function index(Request $request)
   {
      $getLeaveTypes = LeaveType::all();
       return view('leave.index', compact(
         'getLeaveTypes',
     ));
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

    public function listLeaves(Request $request)
   {
      if ($request->ajax()) {
         $query = Leave::query();

         $data = $query->with([])->get();

         return DataTables::of($data)
            ->addColumn('leave_type', function($row)  {
               if ($row->leave_type) {
                  $year = $row->leave_type->leave_type ? 'Year ' . $row->lib->project_year : 'No Year';
                  $amount = number_format($row->lib->dost_fund_amount ?? 0, 2);
                  return "{$year}: {$amount}";
               }
               return null;
            })
            ->addColumn('date_from', function ($row) {
               if (!empty(trim($row->date_from ?? ''))) {
                  return $row->date_from;
               }
               return $row->date_from ?? null;
            })
            ->addColumn('date_to', function ($row) {
               if (!empty(trim($row->date_to ?? ''))) {
                  return $row->date_to;
               }
               return $row->date_to ?? null;
            })
            ->addColumn('reason', fn($row) => $row->reason ?? null)
            ->addColumn('status', fn($row) => $row->status ?? null)
            ->setRowAttr([
               'data-id' => fn($row) => $row->id,
            ])
            ->make(true);
         }

      abort(404);
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