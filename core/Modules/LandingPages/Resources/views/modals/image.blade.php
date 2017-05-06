@extends('landingpages::layouts.modal')

@section('content') 
<a href="javascript:void(0);" class="btn-close onClickClose"></a>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h1>{{ trans('landingpages::global.image') }}</h1>
    </div>
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
        <label for="alt">{{ trans('landingpages::global.alt') }}</label>
          <input type="text" class="form-control" id="alt" name="alt" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="url">{{ trans('landingpages::global.url') }}</label>
          <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="">
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

  $('#img').val($el.attr('src'));
  //$('#url').val($el.attr('href'));
  //$('#target').val($el.attr('target'));

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

  $el.attr('src', $('#img').val());
  //$el.attr('href', $('#url').val());
  //$el.attr('target', $('#target').val());

  // Changes detected
  window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection