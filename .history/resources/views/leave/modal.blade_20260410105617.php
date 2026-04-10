
<div class="modal fade" id="leave-modal" role="dialog" aria-labelledby="leave-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-mdplus" role="document">
    <form id="leave-form">
      @csrf     
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title m-0" id="leave-modal-title">File a Leave</h6>
              <button type="button" class="close " data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="la la-times"></i></span>
              </button>
          </div>
          <div class="modal-body">   
            <div class="form-group row">
              <label for="leave-type-id" class="form-label-fixed col-form-label">Leave Type</label>
              <div class="col">
                <select id="leave-type-id" name="leave-type-id" class="form-control select2">
                  <option value="" selected hidden>Select Leave Type</option>
                  @foreach ($getLeaveTypes as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                  @endforeach
                </select>
                <span id="leave-type-id-feedback" class="invalid-feedback"></span> 
              </div>
            </div> 
            <div class="form-group row">
              <label for="date_from" class="form-label-fixed col-form-label">Date From</label>
              <div class="col-sm-2">
                <input type="text" id="date_from" name="date_from" :value="today()"  class="form-control datepicker">
                <span id="date-from-feedback" class="invalid-feedback"></span> 
              </div>
            </div>
            <div class="form-group row">
              <label for="date" class="form-label-fixed col-form-label">Date</label>
              <div class="col-sm-2">
                <input type="text" id="date_to" name="date_to" :value="today()"  class="form-control datepicker">
                <span id="date-to-feedback" class="invalid-feedback"></span> 
              </div>
            </div>
            
            
            <div class="form-group row">
              <label for="reason" class="form-label-fixed col-form-label">Reason<br>
              <div class="col">             
                <textarea name="reason" id="reason" rows="5" class="form-control"></textarea>
                <span id="reason-feedback" class="invalid-feedback"></span> 
              </div>        
            </div>     

            
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="store-request-status" class="btn btn-outline-primary">Save</button>
          </div>
      </div>
    </form>
  </div>
</div>
