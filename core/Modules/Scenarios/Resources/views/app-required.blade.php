@extends('scenarios::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <h1>{{ trans('scenarios::global.proximity_app') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-6">
      <p class="lead">
        {!! trans('scenarios::global.app_p1') !!}
      </p>
      <p class="lead">
        {!! trans('scenarios::global.app_p2') !!}
      </p>
      <p class="lead">
        {!! trans('scenarios::global.app_p3', ['mailto' => 'mailto:' . $reseller->support_email . '?Subject=' . rawurlencode(trans('scenarios::global.mailto_subject')) . '?Body=' . rawurlencode(trans('scenarios::global.mailto_body'))]) !!}
      </p>

    </div>
    <div class="col-xs-6">
      <img src="{{ url('assets/images/proximity/proximity-app.png') }}" class="img-responsive">
    </div>

    <div style="position: fixed; left: 30px; bottom: 24px;">
      <div class="checkbox checkbox-primary">
        <input name="dont_show_again" id="dont_show_again" type="checkbox" value="1">
        <label for="dont_show_again"> {{ trans('scenarios::global.dont_show_again') }}</label>
      </div>
    </div>

    <div class="editor-modal-footer" style="width:100%">
      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
    </div>

  </div>
</div>
@endsection

@section('script')
<script>
$(function() {
  $('#dont_show_again').on('change', function() {
    if($(this).is(':checked')) {
      setCookie('dont_show_again', 'true', 30);
    } else {
      setCookie('dont_show_again', 'false', 30);
    }
  });
});
</script>
@endsection