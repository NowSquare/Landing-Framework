@extends('landingpages::layouts.modal')

@section('content')
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.link') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.text') }}</label>
          <input type="text" class="form-control" id="text" name="text" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="url">{{ trans('landingpages::global.url') }}</label>
        <div class="input-group">
          <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="" placeholder="http://">
          <div class="input-group-btn add-on">
            <button type="button" class="btn btn-primary" id="select_url" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="url" data-preview="url-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
            <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="url-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
          </div>
        </div>
      </div>

      <div class="form-group">
<?php
echo Former::select('target')
  ->class('select2-required form-control')
  ->name('target')
  ->options([
    '' => trans('landingpages::global.none'), 
    '_blank' => trans('landingpages::global.new_window')
  ])
  ->label(trans('landingpages::global.target'));
?>
      </div>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.cancel') }}</button>
        <button type="button" class="btn btn-primary btn-material onClickUpdate">{{ trans('global.update') }}</button>
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

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  $('#text').val($el.html());
  $('#target').val($el.attr('target')).trigger('change.select2');

  var url = $el.attr('href');
  $('#url').val(url);

  if (url != '') {
    updateImagePreview($('#select_url'));
  }

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

  $el.html($('#text').val());
  $el.attr('href', $('#url').val());
  $el.attr('target', $('#target').val());

    // Changes detected
  window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });

  // Focus window and bind escape to close
  $(window).focus();

  $(document).keyup(function(e) {
    if(e.keyCode === 27) {
      window.parent.lfCloseModal();
    }
  });
});
</script>
@endsection