@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.domain') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <p class="lead">{!! trans('landingpages::global.custom_domain_intro') !!}</p>
    </div>

    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="local_domain">{{ trans('landingpages::global.local_domain') }}</label>
          <input type="text" class="form-control" id="local_domain" name="local_domain" readonly autocomplete="off" value="{{ url('lp/' . $page->site->local_domain) }}">
      </div>

<?php if (Gate::allows('limitation', 'landingpages.custom_domain')) { ?>

      <div class="form-group">
        <label for="domain">{{ trans('landingpages::global.custom_domain') }}</label>
        <div class="input-group">
          <div class="input-group-addon">http://</div>
          <input type="text" class="form-control" id="domain" name="domain" autocomplete="off" value="{{ $page->site->domain }}">
        </div>
        <p class="help-block text-muted"><small>{!! trans('landingpages::global.custom_domain_help', ['host' => request()->getHttpHost()]) !!}</small></p>
      </div>

<?php } else { ?>
        <div class="alert alert-warning">{!! trans('global.no_access_to_feature') !!}</div>
<?php } ?>
    <div class="editor-modal-footer">
      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
<?php if (Gate::allows('limitation', 'landingpages.custom_domain')) { ?>
        <button type="button" class="btn btn-primary btn-material ladda-button onClickUpdate" data-style="zoom-in" data-spinner-color="#138dfa"><span class="ladda-label">{{ trans('global.save') }}</span></button>
<?php } ?>
    </div>

    </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(function() {

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if (Gate::allows('limitation', 'landingpages.custom_domain')) { ?>
    var ladda_button = $('button.onClickUpdate').ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
      url: "{{ url('landingpages/editor/domain') }}",
      data: {domain: $('#domain').val(), sl: "{{ $sl }}",  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      window.parent.lfCloseModal();
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
    });
<?php } else { ?>
    window.parent.lfCloseModal();
<?php } ?>
  });
});
</script>
@endsection