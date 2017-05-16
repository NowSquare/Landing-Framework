@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.domain') }}</h1>
  </div>

  <div class="row">

    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.text') }}</label>
          <input type="text" class="form-control" id="text" name="text" autocomplete="off" value="{{ $page->name }}">
      </div>
      

    <div class="editor-modal-footer">
      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
    </div>

    </div>
  </div>
</div>
@endsection