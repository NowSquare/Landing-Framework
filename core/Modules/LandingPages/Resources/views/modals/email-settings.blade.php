@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.settings') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="row m-b-20">
        <div class="col-xs-12 col-sm-8">
          <div class="form-group">
            <label for="name">{{ trans('landingpages::global.name') }}</label>
              <input type="text" class="form-control" id="name" name="name" autocomplete="off" value="{{ $email->name }}">
          </div>
        </div>
      </div>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
        <button type="button" class="btn btn-primary btn-material ladda-button onClickUpdate" data-style="zoom-in" data-spinner-color="#138dfa"><span class="ladda-label">{{ trans('global.save') }}</span></button>
      </div>

    </div>

  </div>
</div>
@endsection

@section('script')
<script>
$(function() {

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

  var $el = $('html', window.parent.document);

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
    var ladda_button = $('button.onClickUpdate').ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
      url: "{{ url('emailcampaigns/editor/settings') }}",
      data: {name: $('#name').val(), sl: "{{ $sl }}",  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      // Update page title
      if (typeof window.parent.parent.$('#generic_title a') !== 'undefined') {
        window.parent.parent.$('#generic_title a').text($('#name').val());
      }

      // Changes detected
      window.parent.lfSetPageIsDirty();
      window.parent.lfCloseModal();
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
    });

  });
});
</script>
@endsection