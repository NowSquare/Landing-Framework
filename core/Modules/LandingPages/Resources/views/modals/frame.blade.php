@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.frame') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="form-group" id="input-group-src">
        <label for="src">{{ trans('landingpages::global.url') }}</label>
        <textarea class="form-control" id="src" name="src" autocomplete="off" rows="8"></textarea>
        <small class="help-block">{{ trans('landingpages::global.frame_url_help') }}</small>
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