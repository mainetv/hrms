@extends('layouts.app')

@section('content')
    <div class="container-fluid">    
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Document</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form method="POST" action="{{ isset($document->id) ? route('document.update', $document->id) : route('document.store') }}">
                                @csrf
                                @if(isset($document->id))
                                    @method('PUT')
                                @endif
                                <fieldset>
                                    <div class="mb-3">
                                        <label class="form-label">Policy ID</label>
                                        <input type="text" class="form-control" value="{{ $document->number }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date of Issue</label>
                                        <input type="text" class="form-control"  value="{{ $document->date }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control"  value="{{ $document->title }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control h-100" rows="4">{{ $document->detail }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                    <select name="document_type_id" class="default-select form-control wide mb-3">
                                        @foreach($documentTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                {{ $document->document_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->document_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Office/Division</label>
                                        <select name="origin" class="default-select form-control wide mb-3">
                                        @foreach($origins as $origin)
                                            <option value="{{ $origin }}" 
                                                {{ $document->origin == $origin ? 'selected' : '' }}>
                                                {{ $origin->acronym }}
                                            </option>
                                        @endforeach
                                    </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Classification</label>
                                        <div class="mb-3 mb-0">
                                            <label class="radio-inline me-3"><input type="radio" name="optradio"> Internal</label>
                                            <label class="radio-inline me-3"><input type="radio" name="optradio"> External</label>
                                        </div>
                                    <div class="mb-3">
                                        <label class="form-label">Policy?</label>
                                        <div class="mb-3 mb-0">
                                            <label class="radio-inline me-3"><input type="radio" name="optradio"> Yes</label>
                                            <label class="radio-inline me-3"><input type="radio" name="optradio"> No</label>
                                        </div>
                                    <!-- <button type="submit" class="btn btn-primary mt-3">Save</button> -->
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            {{ isset($document->id) ? 'Update' : 'Create' }}
                                        </button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Metadata</h4>
                    </div>
                    <div class="card-body">
                        <div class="metadata">
                            <div class="mb-3">
                                <label class="form-label">Link to File</label>
                                <p><a href="#" class="text-primary">Designation as Officer-in-Charge of IDD.pdf</a></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keywords</label>
                                <select class="multi-select form-control" name="states[]">
                                    <option value="1">Officer-in-Charge</option>
                                    <option value="2">Designation</option>
                                    <option value="3">Exigency of Service</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Related Documents</label>
                                <div class="basic-list-group">
                                    <div class="list-group">
                                        <a href="javascript:void()" class="list-group-item list-group-item-action">Cras
                                    justo odio </a><a href="javascript:void()" class="list-group-item list-group-item-action">Dapibus
                                    ac facilisis in</a> <a href="javascript:void()" class="list-group-item list-group-item-action">Morbi
                                    leo risus</a>
                                        <a href="javascript:void()" class="list-group-item list-group-item-action">Porta
                                    ac consectetur
                                    ac</a> <a href="javascript:void()" class="list-group-item list-group-item-action ">Vestibulum
                                    at eros</a>
                                </div>
                        </div>
                            </div>
                        </div>
                    </div>
                        
                                    
                </div>
            </div>
        </div>
    </div>
@endsection