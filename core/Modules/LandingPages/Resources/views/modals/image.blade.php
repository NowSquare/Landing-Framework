@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.image') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="img">{{ trans('landingpages::global.image') }}</label>
        <div class="input-group">
          <input type="text" class="form-control" id="img" name="img" autocomplete="off" value="">
          <div class="input-group-btn add-on">
            <button type="button" class="btn btn-primary" id="select_img" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="img" data-preview="img-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
            <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="img-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="alt">{{ trans('landingpages::global.title') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.title_help') }}">&#xE887;</i></label>
          <input type="text" class="form-control" id="alt" name="alt" autocomplete="off" value="">
      </div>

<?php if ($link) { ?>

      <div class="form-group">
        <label for="url">{{ trans('landingpages::global.link') }}</label>
        <div class="input-group">
          <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="">
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

<?php } ?>

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

  $('#alt').val($el.attr('alt'));

<?php if ($link) { ?>

  var url = $el.parent('a').attr('href');
  $('#url').val(url);

  if (url != '') {
    updateImagePreview($('#select_url'));
  }

  $('#target').val($el.parent('a').attr('target')).trigger('change.select2');

  function IsValidImageUrl(url, callback) {
    $('<img>', {
      src: url, 
      load: function() { callback(true); }, 
      error: function() { callback(false); }
    });
  }

<?php } ?>

  var img = $el.attr('src');
  $('#img').val(img);

  if (img != '') {
    updateImagePreview($('#select_img'));
  }

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

<?php if ($el_class != '') { ?>

  $el.attr('src', $('#img').val());
  $el.attr('alt', $('#alt').val());

<?php if ($link) { ?>

  $el.parent('a').attr('href', $('#url').val());
  $el.parent('a').attr('target', $('#target').val());

<?php } ?>

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