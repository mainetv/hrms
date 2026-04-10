@php
  $getLeaveTypes = getLeaveTypes();
@endphp
</div>

<form id="request-status-form">
  @csrf 
  <div class="card">
    <div class="card-header">
      <div class="row">
        <h5 class="page-title">Edit Leave</h5>  
        <div class="col-lg-2 ms-auto text-end">          
          <a href="{{ route('project.request_status.index', [$project->id]) }}" class="btn btn-secondary">
            ← Back to List
          </a>     
        </div> 
      </div> 
    </div>    
    <div class="card-body">
      <div class="form-group row">
        <label for="lib-id" class="col-sm-1 col-form-label">LIB</label>
        <div class="col-sm-11">
          <select id="lib-id" name="lib-id" class="form-control select2" {{ $viewAsRoleId == 19 ? 'disabled' : '' }}>
            <option value="" selected hidden>Select LIB</option>
            @foreach ($getLibs as $row)
              <option value="{{ $row->id }}" @if($requestStatus->lib_id==$row->id) {{ "selected" }} @endif>Year {{ $row->project_year }}: {{ formatAmount($row->dost_fund_amount) }}</option>
            @endforeach
          </select>             
          <span id="lib-id-feedback" class="invalid-feedback"></span> 
        </div>                    
      </div>         
      <div class="form-group row">
        <label for="date" class="col-sm-1 col-form-label">Date</label>
        <div class="col-sm-1">
          <input type="text" id="date" name="date"  value="{{ $requestStatus->obligation_date ?? $requestStatus->date }}" class="form-control datepicker">
          <span id="date-feedback" class="invalid-feedback"></span> 
        </div>
        <label for="division" class="col-sm-1 col-form-label">Division</label>
        <div class="col-sm-2">
          <input class="form-control" type="text" id="division" value="{{ $requestStatus->division['acronym'] }}" readonly>
        </div>
        <label for="rs-id" class="col-sm-1 col-form-label">BURS ID</label>          
        <div class="col-sm-2">
          <input class="form-control" type="text" id="rs-id" value="{{ $requestStatus->id }}" readonly>
        </div>
        <label for="rs-no" class="col-sm-1 col-form-label text-right">BURS No.</label>
        <div class="{{ $canGenerateRsNo ? 'col-sm-2' : 'col-sm-3' }}">
          <input class="form-control" type="text" id="rs-no" value="{{ $requestStatus->rs_no }}" @unless($canGenerateRsNo) readonly @endunless>            
        </div>     
        @if($canGenerateRsNo)
          <div class="col-sm-1">
            <button type="button" class="generate-rs-no btn-xs btn-info tippy-btn" title="Generate Request and Status No." >Generate RS No.</button>
          </div>  
        @endif  
      </div>
      <div class="form-group row">
        <label for="payee-id" class="col-sm-1 col-form-label">Payee</label>
        <div class="col-11">
          <select id="payee-id" name="payee-id" class="form-control select2" style="width: 100%;" {{ $viewAsRoleId == 19 ? 'disabled' : '' }}>
            <option value="" selected hidden>Select Payee</option>
            @foreach ($getPayees as $row)
              @php
                $bank = $row->bank;
                $fullText = $row->payee . ' [' . ($bank->bank_acronym ?? '') . ': ' . $row->bank_account_name . ' | ' . $row->bank_account_no . ']';
              @endphp
              <option value="{{ $row->id }}" @if($requestStatus->payee_id==$row->id) {{ "selected" }} @endif>{{ $fullText }}</option>
            @endforeach
          </select>
          <span id="payee-id-feedback" class="invalid-feedback"></span> 
        </div> 
      </div> 
      <div class="form-group row">
        <label for="particulars" class="col-sm-1 col-form-label">Particulars<br>
          <sub><a href="#" class="insert-particulars-template-button" data-toggle="tooltip" data-placement='auto' 
          title='Insert particulars template'>Template</a></sub></label>
        <div class="col">             
          <textarea name="particulars" id="particulars" rows="4" class="form-control">{{ $requestStatus->particulars }}</textarea>
          <span id="particulars-feedback" class="invalid-feedback"></span> 
        </div>        
      </div>     

      @if($canAttachCharging)
      <strong>Charged to:</strong>
      @endif
      <table id="attached-charging-table" class="table-xs table-bordered text-center" style="width: 100%;">    
        <thead>
          <th style="min-width:6%; max-width:6%;">Division</th>
          <th style="min-width:78%; max-width:78%;">Program/Project</th>
          <th style="min-width:13%; max-width:13%;">Amount</th>
          <th style="min-width:3%; max-width:3%;">
            <button type="button" title="Attach charging" data-action="attach_charging"
                class="attach-button btn-xs btn btn-outline-primary">
                <i class="fas fa-plus"></i>
              </button>
          </th>
        </thead>
        <tfoot>
          <th colspan="2" class="text-right">TOTAL</th>
          <th class="text-right"><span id="total-charging">0.00</span></th>
        </tfoot>
      </table>

      @if($canAttachAllotment)
        <br>
        <strong>Attached Allotment</strong>
        <table id="attached-allotment-table" class="table-xs table-bordered text-center" style="width: 100%;">    
            <thead>
                <th style="min-width:6%; max-width:6%;">Division</th>
                <th style="min-width:23%; max-width:23%;">Fund</th>
                <th style="min-width:55%; max-width:4557%;">Program - Project / Account Code</th>
                <th style="min-width:13%; max-width:13%;">Amount</th>
                <th style="min-width:3%; max-width:3%;">
                  <button type="button" title="Attach allotment" data-action="attach_allotment"
                      class="attach-button btn-xs btn btn-outline-primary">
                      <i class="fas fa-plus"></i>
                  </button>
                </th>
            </thead>
            <tfoot>
              <th colspan="3" class="text-right">TOTAL</th>
              <th class="text-right"><span id="total-allotment">0.00</span></th>
            </tfoot>
        </table>
      @endcan

      <br>       

      @if($canSelectSignatoryA)      
        <label class="col-form-label">A. Certified</label>
        @foreach (range(1, 10) as $i)
          @if ($i % 2 == 1)
            <div class="form-group row">
          @endif

            <label class="col-1 col-form-label text-right">{{ $i }}.</label>
            <div class="col-5">
              <x-select2
                name="signatory{{ $i }}"
                id="signatory{{ $i }}"
                :options="$getRequestStatusSignatories"
                :selected="old('signatory' . $i, $requestStatus->{'signatory' . $i . '_id'} ?? null)"
                placeholder="Select Signatory"
                class="form-control select2"
              />
            </div>

          @if ($i % 2 == 0 || $i == 10)
            </div>
          @endif
        @endforeach
      @endif
      @if($canSelectSignatoryB)        
        <label class="col-form-label">B. Certified</label>

        @foreach (range(1, 1) as $i)
        <div class="form-group row">
            <label class="col-1 col-form-label text-right">{{ $i }}.</label>
            <div class="col-5">
                <x-select2
                    name="signatory_b{{ $i }}"
                    id="signatory_b{{ $i }}"
                    :options="$getRequestStatusSignatories"
                    :selected="null"
                    placeholder="Select Signatory"
                    class="form-control select2"
                />
            </div>
        </div>
        @endforeach
      @endif
      
      @if($canAttachAllotment)
          <br>
          <strong>NORSA</strong>              
          <table id="attached-notice-adjustment-table" class="table-xs table-bordered text-center" style="width: 100%;">    
            <thead>
              <th style="min-width:6%; max-width:6%;">Division</th>
              <th style="min-width:20%; max-width:20%;">Fund</th>
              <th style="min-width:38%; max-width:38%;">Program - Project / Account Code</th>                        
              <th style="min-width:13%; max-width:13%;">Amount</th>
              <th style="min-width:10%; max-width:10%;">NORSA No.</th>
              <th style="min-width:10%; max-width:10%;">NORSA Date</th>
              <th style="min-width:3%; max-width:3%;">
                <button type="button" title="Add notice adjustment" data-action="attach_notice_adjustment"
                    class="attach-button btn-xs btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                </button>
              </th>
            </thead>  
            <tfoot>
              <th colspan="5" class="text-right">TOTAL</th>
              <th class="text-right"><span id="total-notice-adjustment">0.00</span></th>
            </tfoot>
          </table>
          <br>
          <div class="form-group d-flex justify-content-end info">
            <label for="" class="col-2">TOTAL AMOUNT</label>
            <label id="total-allotment-notice-adjustment" class="fw-bold text-right total-highlight">0.00</label>
          </div>
      @endif

      <div class="form-group row">
        <label class="col-sm-1 col-form-label">Transaction Type</label>
        <div class="col">    
          <select name="rs-transaction-type-id[]" id="rs-transaction-type-id" multiple="multiple" 
            data-placeholder="Select Transaction Type" class="form-control select2">
            @foreach($getRsTransactionTypes->groupBy(fn($item) => optional($item->allotmentClass)->allotment_class) as $key => $group)
                <optgroup class="font-weight-bold" label="{{ $key }}">
                    @foreach($group as $item)               
                        <option value="{{ $item->id }}" {{ in_array($item->id, $selectedTransactionTypeIds) ? 'selected' : '' }}>
                          {{ $item->transaction_type }} 
                        </option>                       
                    @endforeach
                </optgroup>
            @endforeach  
          </select>
        </div> 
      </div> 
    </div>  
    <div class="card-footer">
      <div class="d-flex align-items-center justify-content-between">
        
        <!-- Left -->
        <div>
          <a href="{{ route('project.request_status.index', [$project->id]) }}" class="btn btn-secondary">
            ← Back to List
          </a>
        </div>
        
        <!-- Center -->
        <div class="d-flex justify-content-center flex-grow-1 gap-1">
          <button type="button" 
                class="btn btn-soft-info print-button" 
                style="width:150px;" 
                data-url="{{ route('financial.print.requestStatus', $requestStatus->id) }}">
            <i class="fas fa-print"></i> Print
          </button>
          <button type="button" 
                class="btn btn-soft-info print-button" 
                style="width:150px;" 
                data-url="{{ route('financial.print.requestStatus', $requestStatus->id) }}">
            <i class="fas fa-print"></i> Print
          </button>

          {{-- <a href="{{ route('financial.request_status.show', $requestStatus->id) }}" target="_blank">Test PDF</a> --}}

          <button type="button" id="update-request-status" class="btn btn-soft-primary" style="width:200px;">
            <i class="fas fa-save"></i> Save
          </button>
        </div>
                  
        <!-- Right (balances the layout) -->
        <div style="width: 120px;"></div>
        
      </div>
    </div>
    
  </div>   
</form>

@include('financial.modals.allotment_list')

@push('scripts')
  <script type="text/javascript">
    $(document).ready(function () {    
      const rsId = "{{ $requestStatus->id }}";
      const viewAsRoleId = "{{ $viewAsRoleId }}";
      const viewAsDivisionId = "{{ $viewAsDivisionId }}";
      
      const canAttachCharging = {{ $canAttachCharging ? 'true' : 'false' }};
      const canAttachAllotment = {{ $canAttachAllotment ? 'true' : 'false' }}; 

      const requiredFields = [
        { id: 'lib-id', event: 'change', isSelect: true },
        { id: 'date', event: 'change', isSelect: true },
        { id: 'payee-id', event: 'change', isSelect: true },
        { id: 'particulars', event: 'input' },
      ];      

      function actionButtons(id, action) {
        if(action=="charging"){
          return `<center style="white-space:nowrap">
                  <button type='button' class="btn-xs remove-attached-charging btn btn-outline-danger" title="Remove attached charging" data-id="${id}">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </center>`;
        }
        else if(action=="allotment"){
          return `<center style="white-space:nowrap">
                    <button type='button' class="btn-xs remove-attached-allotment btn btn-outline-danger" title="Remove attached charging" data-id="${id}">
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </center>`;
        }
        else if(action=="notice_adjustment"){
          return `<center style="white-space:nowrap">
                    <button type='button' class="btn-xs remove-attached-notice-adjustment btn btn-outline-danger" title="Remove notice adjustment" data-id="${id}">
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </center>`;
        }
      }     
      
      @include('scripts.validation') 

      //Function for loading tables of attached charging and allotment
        let attachedChargingTable;  
        attachedChargingTable = $('#attached-charging-table').DataTable({
          info: false,
          destroy: true, 
          searching: false,
          processing: true,
          serverSide: false,
          lengthChange: false,
          paging: false, 
          ajax: {
            url: '{{ route('listAttachedChargingByRequestStatus') }}',
            data: function(d) {
              d.rs_id = rsId;
              d.view_as_role_id = viewAsRoleId;
              d.view_as_division_id = viewAsDivisionId;
            },
            dataSrc: 'data'
          },
          columns: [
            { data: 'division', title: 'Division'},
            { data: 'activity_charging', title: 'Program/Project', className: 'text-left' },
            { 
              data: 'amount',
              title: 'Amount',
              render: function(data, type, row) {
                let readonly = !canAttachCharging;
                return `<input type="text" class="form-control amount charging-amount text-right" data-id="${row.id}" value="${row.amount ?? 0}" ${readonly ? 'readonly' : ''}>`;
              }
            },
            {
                data: 'id',
                title: '',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return actionButtons(row.id, 'charging');
                }
            }
          ],
          initComplete: function() {
            if (canAttachCharging) {
              $('#attached-charging-table thead th:last').html(`
                <button type="button" title="Attach charging" data-action="attach_charging" 
                    class="attach-button btn-xs btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                </button>
              `);
            }
          }
        });   

        let attachedAllotmentTable;     
        attachedAllotmentTable = $('#attached-allotment-table').DataTable({
          info: false,
          destroy: true, 
          searching: false,
          processing: true,
          serverSide: false,
          lengthChange: false,
          paging: false, 
          ajax: {
            url: '{{ route('listAttachedAllotmentByRequestStatus') }}',
            data: function(d) {
              d.rs_id = rsId;
              d.view_as_role_id = viewAsRoleId;
              d.view_as_division_id = viewAsDivisionId;
            },
            dataSrc: 'data'
          },
          columns: [
            { data: 'division', title: 'Division' },
            { data: 'fund_category', title: 'Fund', className: 'text-left' },
            { data: 'activity_charging', title: 'Program/Project', className: 'text-left'},
            { 
              data: 'amount',
              title: 'Amount',
              render: function(data, type, row) {
                return `<input type="text" class="form-control amount allotment-amount text-right" data-id="${row.id}" value="${row.amount ?? 0}">`;
              }
            },
            {             
              data: "id",
              defaultContent: '', 
              orderable: false,
              render: function(data, type, row) {                
                return actionButtons(row.id, 'allotment');
                  
              }
            },  
          ],
          initComplete: function() {
            if (canAttachCharging) {
              $('#attached-allotment-table thead th:last').html(`
                <button type="button" title="Attach allotment" data-action="attach_allotment" 
                    class="attach-button btn-xs btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                </button>
              `);
            }
          }
        });

        let attachedNoticeAdjustmentTable;     
        attachedNoticeAdjustmentTable = $('#attached-notice-adjustment-table').DataTable({
          info: false,
          destroy: true, 
          searching: false,
          processing: true,
          serverSide: false,
          lengthChange: false,
          paging: false, 
          ajax: {
            url: '{{ route('listNoticeAdjustmentByRequestStatus') }}',
            data: function(d) {
              d.rs_id = rsId;
              d.view_as_role_id = viewAsRoleId;
              d.view_as_division_id = viewAsDivisionId;
            },
            dataSrc: 'data'
          },
          columns: [
            { data: 'division', title: 'Division' },
            { data: 'fund_category', title: 'Fund', className: 'text-left'},
            { data: 'activity_charging', title: 'Program/Project', className: 'text-left'},
            { 
              data: 'notice_adjustment_no',
              title: 'NORSA No.',
              render: function(data, type, row) {
                return `<input type="text" class="form-control notice-adjustment-no" data-id="${row.id}" value="${row.notice_adjustment_no ?? 0}">`;
              }
            },   
            { 
              data: 'notice_adjustment_date',
              title: 'NORSA Date',
              render: function(data, type, row) {
                  let value = data ? new Date(data).toISOString().split('T')[0] : '';
                  return `<input type="date" class="form-control notice-adjustment-date" data-id="${row.id}" value="${value}">`;
              }
            },  
            { 
              data: 'amount',
              title: 'Amount',
              render: function(data, type, row) {
                return `<input type="text" class="form-control amount notice-adjustment-amount text-right" data-id="${row.id}" value="${row.amount ?? 0}">`;
              }
            },
            {             
              data: "id",
              defaultContent: '', 
              orderable: false,
              render: function(data, type, row) {                
                return actionButtons(row.id, 'notice_adjustment');
                  
              }
            },  
          ],
          initComplete: function() {
            if (canAttachCharging) {
              $('#attached-notice-adjustment-table thead th:last').html(`
                <button type="button" title="Add notice adjustment" data-action="attach_notice_adjustment" 
                    class="attach-button btn-xs btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                </button>
              `);
            }
          }
        });       

      //Calculate totals
        function getTableTotal(tableSelector, inputClass) {
          let total = 0;
          $(`${tableSelector} tbody tr`).each(function () {
              const val = $(this).find(inputClass).val() || "0";
              total += parseFloat(val.toString().replace(/,/g, "")) || 0;
          });
          return total;
        }

        function updateTotals() {
          const totalChargings = getTableTotal('#attached-charging-table', '.charging-amount');
          const totalAllotments = getTableTotal('#attached-allotment-table', '.allotment-amount');
          const totalNoticeAdjustments = getTableTotal('#attached-notice-adjustment-table', '.notice-adjustment-amount');

          $('#total-charging').text(formatAmount(totalChargings));
          $('#total-allotment').text(formatAmount(totalAllotments));
          $('#total-notice-adjustment').text(formatAmount(totalNoticeAdjustments));

          const grandTotal = totalAllotments + totalNoticeAdjustments;
          $('#total-allotment-notice-adjustment').text(formatAmount(grandTotal));
        }

        $(document).on('input', '.charging-amount, .allotment-amount, .notice-adjustment-amount', function () {
            let val = this.value.replace(/,/g, "").replace(/[^0-9.-]/g, "").replace(/(?!^)-/g, "").replace(/(\..*)\./g, "$1");
            this.value = val;
            updateTotals();
        });

        $(document).on('blur', '.charging-amount, .allotment-amount, .notice-adjustment-amount', function () {
            this.value = formatAmount(this.value);
            updateTotals();
        });

        attachedChargingTable.on('draw.dt', function() {
            updateTotals();
            // Reformat inputs after draw
            $('#attached-charging-table .charging-amount').each(function() {
                this.value = formatAmount(this.value);
            });
        });

        attachedAllotmentTable.on('draw.dt', function() {
            updateTotals();
            $('#attached-allotment-table .allotment-amount').each(function() {
                this.value = formatAmount(this.value);
            });
        });

        attachedNoticeAdjustmentTable.on('draw.dt', function() {
            updateTotals();
            $('#attached-notice-adjustment-table .notice-adjustment-amount').each(function() {
                this.value = formatAmount(this.value);
            });
        });
        attachedChargingTable.on('init.dt', updateTotals);
        attachedAllotmentTable.on('init.dt', updateTotals);
        attachedNoticeAdjustmentTable.on('init.dt', updateTotals);

      // Function for loading allotment/charging table in the modal
        let allotmentTable;
        let action;

        $(document).on('click', '.attach-button', function (e) {
          e.preventDefault();
          action = $(this).data('action');
          let title = 'Select Charging';
          if (action === 'attach_allotment') {
              title = 'Select Allotment Charging';
          } else if (action === 'attach_notice_adjustment') {
              title = 'Select Notice Adjustment Charging';
          }
          $('#attach-allotment-modal .modal-title').text(title);
          $('#attach-allotment-modal').modal('show');
        });

        $('#attach-allotment-modal').on('shown.bs.modal', function () {
            loadAllotmentTable(action);
        });

        function loadAllotmentTable(action) {   
          const isAttachCharging = action === 'attach_charging';
          const isAllotmentMode  = ['attach_allotment', 'attach_notice_adjustment'].includes(action);     

          if ($.fn.DataTable.isDataTable('#allotment-table')) {
            $('#allotment-table').DataTable().destroy();
            $('#allotment-table thead').empty();
            $('#allotment-table tbody').empty();
          }
          let requestTypeId = '2';
          allotmentTable = $('#allotment-table').DataTable({
            info: false,
            orderCellsTop: true,
            pageLength: 10,
            lengthChange: false,
            ordering: false,
            processing: true,
            serverSide: false,
            ajax: {
              url: '{{ route('listAllAllotment') }}',  
              method: "GET",
              data: { request_type_id: requestTypeId, action: action }   
            },
            columns: isAttachCharging ? [
              { data: 'division', title: 'Division'},
              { data: 'click_label', title: 'Activity'},
            ] : [
              { data: 'division', title: 'Division'},
              { data: 'fund_category_group', title: 'Fund'},
              { data: 'fund_category_group', visible: false },
              { data: 'activity_group', visible: false },
              { data: 'click_label', title: 'Activity - Subactivity / Code'},
            ],
            columnDefs: isAttachCharging ? [
              { targets: [0], className: 'text-center' },
              { targets: [1], className: 'text-left' },
                
            ] : [
              { visible: false, targets: 2 },
              { visible: false, targets: 3 },             
              { targets: [0], className: 'text-center' },
              { targets: [1, 4], className: 'text-left' },
            ],
            drawCallback: function () {
              const api = this.api();
              if (isAttachCharging) {
                const allRows = api.rows({ search: 'applied' }).nodes();
                const allData = api.rows({ search: 'applied' }).data();

                let lastActivity = null;

                allData.each(function (data, i) {
                    const row = $(allRows[i]);
                    if (data.activity_group !== lastActivity) {
                        $(row).before(`
                            <tr class="fw-bold">
                                <td></td>
                                <td class="text-left fw-bold" colspan="2">${data.activity_group}</td>
                            </tr>
                        `);
                        lastActivity = data.activity_group;
                    }
                    $('td:eq(1) a.attach-charging', row).css({
                        'padding-left': '20px',
                    });
                });
              }
              if (isAllotmentMode) {
                const allRows = api.rows({ search: 'applied' }).nodes(); // all filtered rows
                const allData = api.rows({ search: 'applied' }).data();

                let lastDivision = null;
                let lastFund = null;
                let lastActivity = null;

                allData.each(function (data, i) {
                    const row = $(allRows[i]);

                    // 🔹 Reset fund & activity when division changes
                    if (data.division !== lastDivision) {
                        lastDivision = data.division;
                        lastFund = null;
                        lastActivity = null;
                    }

                    // 🔹 Fund header (only if changed within this division)
                    if (data.fund_category_group !== lastFund) {
                        row.before(`
                            <tr class="table-secondary fw-bold">
                                <td></td>
                                <td colspan="4" class="text-left fw-bold">${data.fund_category_group}</td>
                            </tr>
                        `);
                        lastFund = data.fund_category_group;
                        lastActivity = null;
                    }

                    if (data.activity_group !== lastActivity) {
                        row.before(`
                            <tr class="fw-bold">
                                <td></td>
                                <td></td>
                                <td colspan="3" class="text-left fw-bold">${data.activity_group}</td>
                            </tr>
                        `);
                        lastActivity = data.activity_group;
                    }
                    
                    $('td:eq(1)', row).text('');
                });
              }            
            },
            initComplete: function () {
                $('#allotment-table_filter input')
                    .attr('placeholder', 'Search program/project');

                // Add column filter dropdown to first column (Division)
                this.api().columns([0]).every(function () {
                    var column = this;
                    $(column.header()).append("<br>");
                    var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.header()))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });
            }
          });
        }

      // Function for remove attached charging (charging or allotment)
        confirmAndDelete({
          tableSelector: '#attached-charging-table',
          buttonClass: '.remove-attached-charging',
          destroyRoute: '{{ route('financial.request_status.charging.destroy', ':id') }}',
          dataTableInstance: attachedChargingTable,
          confirmTitle: 'Are you sure you want to remove attached charging?',
          successMessage: 'Attached charging removed successfully.'
        });
        confirmAndDelete({
            tableSelector: '#attached-allotment-table',
            buttonClass: '.remove-attached-allotment',
            destroyRoute: '{{ route('financial.request_status.allotment.destroy', ':id') }}',
            dataTableInstance: attachedAllotmentTable,
            confirmTitle: 'Are you sure you want to remove attached allotment charging?',
            successMessage: 'Attached allotment charging removed successfully.'
        });   
        confirmAndDelete({
            tableSelector: '#attached-notice-adjustment-table',
            buttonClass: '.remove-attached-notice-adjustment',
            destroyRoute: '{{ route('financial.request_status.allotment.destroy', ':id') }}',
            dataTableInstance: attachedNoticeAdjustmentTable,
            confirmTitle: 'Are you sure you want to remove notice adjustment?',
            successMessage: 'Notice adjustment charging removed successfully.'
        });   
        function confirmAndDelete({
          tableSelector,
          buttonClass,
          destroyRoute,
          dataTableInstance,
          confirmTitle = 'Are you sure?',
          successMessage  = 'Removed successfully'
        }) {
          $(document).on('click', `${tableSelector} ${buttonClass}`, function (e) {
              e.preventDefault();

              const id = $(this).data('id');

              Swal.fire({
                  title: confirmTitle,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes'
              }).then(({ isConfirmed }) => {
                  if (!isConfirmed) return;

                  $.ajax({
                      type: 'DELETE',
                      url: destroyRoute.replace(':id', id),
                      success(response) {
                          dataTableInstance.ajax.reload(null, false);
                          Swal.fire({
                              position: 'top-end',
                              icon: 'success',
                              title: successMessage,
                              showConfirmButton: false,
                              timer: 1500
                          });
                      },
                      error(xhr) {
                          Swal.fire({
                              icon: 'error',
                              title: 'Error',
                              text: xhr.responseJSON?.message || 'Something went wrong.'
                          });
                      }
                  });
              });
          });
        }

      // Function for attach charging (charging or allotment)
        function handleAttach(action, buttonClass, ajaxUrl, tableInstance) {
          $(document).on('click', buttonClass, function(e) {
              e.preventDefault();
              const allotmentId = $(this).data('id');

              $.post(ajaxUrl, {
                  _token: '{{ csrf_token() }}',
                  rs_id: rsId,
                  allotment_id: allotmentId,
                  action: action
              }, function(response) {
                  tableInstance.ajax.reload(null, false);
                  Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: response.message,
                      showConfirmButton: false,
                      timer: 1500
                  });

                  $('#attach-allotment-modal').modal('toggle');
              });
          });
        }
        handleAttach('attach_charging','.attach-charging', "{{ route('financial.request_status.charging.store') }}", attachedChargingTable);
        handleAttach('attach_allotment', '.attach-allotment', "{{ route('financial.request_status.allotment.store') }}", attachedAllotmentTable);
        handleAttach('attach_notice_adjustment', '.attach-notice-adjustment', "{{ route('financial.request_status.allotment.store') }}", attachedNoticeAdjustmentTable);
      
      $('#update-request-status').off('click').on('click', function(e){
        e.preventDefault();
        let formData = $('#request-status-form').serializeArray();
        let chargings = [];
        $('input.amount.charging-amount').each(function () {
            chargings.push({
                id: $(this).data('id'),
                amount: $(this).val()
            });
        });
        let allotments = [];
        $('input.amount.allotment-amount').each(function () {
            allotments.push({
                id: $(this).data('id'),
                amount: $(this).val()
            });
        });
        let noticeAdjustments = [];
        $('.notice-adjustment-amount').each(function () {
          const id = $(this).data('id');
          noticeAdjustments.push({
              id: $(this).data('id'),
              notice_adjustment_no: $(`.notice-adjustment-no[data-id="${id}"]`).val(),
              notice_adjustment_date: $(`.notice-adjustment-date[data-id="${id}"]`).val(),
              amount: $(this).val()
          });
        });
        formData.push({ name: 'chargings', value: JSON.stringify(chargings) });
        formData.push({ name: 'allotments', value: JSON.stringify(allotments) });
        formData.push({ name: 'noticeAdjustments', value: JSON.stringify(noticeAdjustments) });
        $.ajax({
          method: 'PUT',
          url: '{{ route('project.request_status.update', ['project' => $project->id, 'requestStatus' => $requestStatus->id]) }}',      
          data: formData,
          success:function(response) {                                       
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: response.message,
              showConfirmButton: false,
              timer: 1500
            })                
          },            
          error: function (xhr) {
            var response = JSON.parse(xhr.responseText);
            validationInline(response, requiredFields);
          }
        }); 
      }); 
      
      $('.generate-rs-no').click(function(){
        let formData = $('#request-status-form').serializeArray();
        formData.push({ name: 'generate_rs_no', value: true });
        $.ajax({
          method: 'PUT',
          url: '{{ route('financial.request_status.update', ['requestStatus' => $requestStatus->id]) }}',    
          data: formData,
          success:function(response) { 
            if (response.rs_no) {
                $('#rs-no').val(response.rs_no);
            }   
            Swal.fire({
              icon: 'success',
              title: response.message,
              timer: 1500,
              showConfirmButton: false
            });
          },
        });
      });

    });
  </script>
@endpush
