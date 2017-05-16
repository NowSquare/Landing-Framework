@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.seo') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.name') }}</label>
          <input type="text" class="form-control" id="name" name="name" autocomplete="off" value="{{ $page->name }}">
          <p class="help-block">{!! trans('landingpages::global.name_help') !!}</p>
      </div>

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.page_title') }}</label>
          <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="">
          <p class="help-block">{!! trans('landingpages::global.page_title_help') !!}</p>
      </div>

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.page_description') }}</label>
          <textarea class="form-control" id="description" name="description" autocomplete="off"></textarea>
          <p class="help-block">{!! trans('landingpages::global.page_description_help') !!}</p>
      </div>


      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material ladda-button onClickUpdate" data-style="zoom-in" data-spinner-color="#138dfa"><span class="ladda-label">{{ trans('global.save') }}</span></button>
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
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

  $('#title').val($el.find('title').text());
  $('#description').val($el.find('meta[name=description]').attr('content'));

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
    var ladda_button = $('button.onClickUpdate').ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
      url: "{{ url('landingpages/editor/seo') }}",
      data: {name: $('#name').val(), sl: "{{ $sl }}",  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      $el.find('title').text($('#title').val());
      $el.find('meta[name=description]').attr('content', $('#description').val());

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