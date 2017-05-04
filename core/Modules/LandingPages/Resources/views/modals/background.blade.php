@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h1>Background</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-8">
      <form>

        <div class="form-group">
          <label for="logo_square">{{ trans('global.logo_square') }}</label>
          <div class="input-group">
            <input type="text" class="form-control" id="logo_square" name="logo_square" autocomplete="off" value="">
            <div class="input-group-btn add-on">
              <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="logo_square" data-preview="logo_square-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
              <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="logo_square-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="bg-image">Background image</label>
          <input type="text" class="form-control" id="bg-image">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Background color</label>
          <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>

        <button type="submit" class="btn btn-primary btn-material" onclick="parent.$modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-material">Save</button>
      </form>
    </div>
  </div>
</div>

@endsection