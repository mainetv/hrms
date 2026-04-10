
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
              <label for="rs-date" class="form-label-fixed col-form-label">Date</label>
              <div class="col-sm-2">
                <input type="text" id="date" name="date" :value="today()"  class="form-control datepicker">
                <span id="rs-date-feedback" class="invalid-feedback"></span> 
              </div>
            </div>
            
            <div class="form-group row">
              <label for="payee-id" class="form-label-fixed col-form-label">Payee</label>
              <div class="col">
                <select id="payee-id" name="payee-id" class="form-control select2">
                  <option value="" selected hidden>Select Payee</option>
                  @foreach ($getPayees as $row)
                    @php
                      $bank = $row->bank;
                      $fullText = $row->payee . ' [' . ($bank->bank_acronym ?? '') . ': ' . $row->bank_account_name . ' | ' . $row->bank_account_no . ']';
                    @endphp
                    <option value="{{ $row->id }}">{{ $fullText }}</option>
                  @endforeach
                </select>
                <span id="payee-id-feedback" class="invalid-feedback"></span> 
              </div> 
            </div> 
            <div class="form-group row">
              <label for="particulars" class="form-label-fixed col-form-label">Particulars<br>
                <sub><a href="#" class="insert-particulars-template-button" data-toggle="tooltip" data-placement='auto' 
                title='Insert particulars template' data-rs-type-id="">Template</a></sub></label>
              <div class="col">             
                <textarea name="particulars" id="particulars" rows="5" class="form-control rs-field"></textarea>
                <span id="particulars-feedback" class="invalid-feedback"></span> 
              </div>        
            </div>     

            <div class="form-group row">
              <label class="form-label-fixed col-form-label">Transaction<br>Type</label>
              <div class="col">    
                <select name="rs-transaction-type-id[]" id="rs-transaction-type-id" multiple="multiple" 
                  data-placeholder="Select Transaction Type" class="form-control select2">
                  @foreach($getRsTransactionTypes->groupBy(fn($item) => optional($item->allotmentClass)->allotment_class) as $key => $group)
                      <optgroup class="font-weight-bold" label="{{ $key }}">
                          @foreach($group as $item)               
                              <option value="{{ $item->id }}">
                                  {{ $item->transaction_type }} 
                              </option>                        
                          @endforeach
                      </optgroup>
                  @endforeach  
                </select>

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
