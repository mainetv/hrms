@php
  $getLeaveTypes = getLeaveTypes();
@endphp

<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-11">
        <h5 class="page-title">Budget Utilization Request and Status</h5>
      </div>
        <button class="btn btn-primary" id="add-button">File a Leave</button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="request-status-table" class="table table-bordered dt-responsive table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
          <thead>
            <tr>                 
              <th>ID</th>
              <th>Leave Type</th>
              <th>Date To</th>
              <th>Date From</th>              
              <th>Reason</th>    
              <th>Status</th>                 
              <th></th>
            </tr>
          </thead>
      </table>
    </div>

  </div>
</div>

@include('leave.modal')

@section('jscript')
  <script type="text/javascript">  
    $(document).ready(function(){ 
      const projectId = "{{ $project->id }}";

      const requiredFields = [
        { id: 'lib-id', event: 'change', isSelect: true },
        { id: 'date', event: 'change', isSelect: true },
        { id: 'payee-id', event: 'change', isSelect: true },
        { id: 'particulars', event: 'input' },
      ];

      @include('scripts.validation')
      
      function actionButtons(id) {
        let url = "{{ route('project.request_status.edit', ['project' => $project->id, 'requestStatus' => 'rsId']) }}"
        .replace('rsId', id);
        return `<center style="white-space:nowrap">
                  <a type='button' class="view-button btn btn-xs btn-outline-dark tippy-btn" title="View BURS" href="${url}">
                    <i class="fas fa-eye"></i>
                  </a>
                   <a type='button' class="delete-button btn btn-xs btn-outline-danger tippy-btn" title="Delete BURS" href="${url}">
                    <i class="fas fa-trash"></i>
                  </a>
                </center>`;
      }      

      var requestStatusTable = $('#request-status-table').DataTable({
        destroy: true,
        info: true,
        fixedColumns: true,
        processing: true,
        responsive: true,
        ajax: {
          url: '{{ route('listRequestStatusesByProject') }}',
          type: 'GET',
          data: function(d) {
            d.project_id = projectId;
          },
          dataSrc: 'data'
        },
        order: [[6, 'asc']],
        columns: [ 
          { data: 'lib' },
          { data: 'date' },
          { data: 'rs_no', className: 'dt-nowrap'},
          { data: 'division_acronym' },
          { data: 'payee_name' },
          { data: 'particulars' },
          { data: 'amount', className: 'text-right' },
          {             
            data: "id",
            defaultContent: '', 
            orderable: false,
            render: function(data, type, row) {
              return actionButtons(row.id);
            }
          },  
        ],
      });    
      
      $('#add-button').click(function (e) {  
        e.preventDefault();    
        $('#request-status-modal').modal('toggle');        
      }); 

      $('#store-request-status').click(function (e) {
        $.ajax({
          method: 'POST',
          url: '{{ route('project.request_status.store', ['project' => $project->id]) }}',      
          data: $('#request-status-form').serializeArray(),  
          success:function(response) { 
            $('#request-status-modal').modal('toggle');   
            window.location.href = response.redirect;  
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: response.message,
              showConfirmButton: false,
              timer: 1500            
            });
                             
          },            
          error: function (xhr) {
            var response = JSON.parse(xhr.responseText);
            validationInline(response, requiredFields);
          }
        }); 
      });  
    })  
  </script>
@endsection

