@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.insert_block') }}</h1>
  </div>

  <div class="row">
<?php
foreach($categories as $category) {
?>
    <div class="col-xs-12 col-sm-4 col-lg-3">

      <div class="portlet shadow-box">
        <div class="portlet-heading portlet-default" style="padding-bottom: 0">
          <h3 class="portlet-title text-dark text-center" style="float: none">{{ $category['name'] }}</h3>
        </div>
        <div>
          <div class="text-center" style="margin-bottom: 1rem">
            <a href="{{ url('landingpages/editor/modal/insert-block-select?el_class=' . $el_class . '&position=' . $position . '&c=' . $category['dir']) }}">
              <img src="{{ $category['icon'] }}" class="box-icon" alt="{{ $category['name'] }}">
            </a>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>

    </div>
<?php
}
?>
    <div class="col-xs-10 col-sm-6">
      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>
    </div>

  </div>
</div>
@endsection