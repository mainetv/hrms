

<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-11">
        <h5 class="page-title">Leave Requests</h5>
      </div>
        <button class="btn btn-primary" id="add-button">File a Leave</button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="leave-table" class="table table-bordered dt-responsive table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
      var leaveTable = $('#leave-table').DataTable({
        destroy: true,
        info: true,
        fixedColumns: true,
        processing: true,
        responsive: true,
        ajax: {
          url: '{{ route('listLeaves') }}',
          type: 'GET',
          data: function(d) {
            d.user_id = user_id;
          },
          dataSrc: 'data'
        },
        order: [[6, 'asc']],
        columns: [ 
          { data: 'id' },
          { data: 'leave_type' },
          { data: 'date_from' },
          { data: 'date_to' },
          { data: 'reason' },
          { data: 'status' },
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
        $('#leave-modal').modal('toggle');        
      }); 

      $('#store-request-status').click(function (e) {
        $.ajax({
          method: 'POST',
          url: '{{ route('project.request_status.store', ['project' => $project->id]) }}',      
          data: $('#leave-form').serializeArray(),  
          success:function(response) { 
            $('#leave-modal').modal('toggle');   
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

