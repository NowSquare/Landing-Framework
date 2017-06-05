@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.video') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="src">{{ trans('landingpages::global.url') }}</label>
        <input type="text" class="form-control" id="src" name="src" autocomplete="off" value="">
<?php /*
        <div class="input-group">
          <input type="text" class="form-control" id="src" name="src" autocomplete="off" value="">
          <div class="input-group-btn add-on">
            <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="src-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
          </div>
        </div>*/ ?>
<?php /*        <small class="text-muted">{{ trans('landingpages::global.video_url_help') }}</small>*/ ?>
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

  var src = $el.find('iframe').attr('src');
  $('#src').val(src);

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

<?php if ($el_class != '') { ?>

    $el.find('iframe').attr('src', $('#src').val());

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection