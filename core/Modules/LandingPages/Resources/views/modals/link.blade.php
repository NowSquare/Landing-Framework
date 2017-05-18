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
<?php if (! $submit) { ?>
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

<?php } ?>

<?php if ($color) { ?>
      <div class="form-group">
        <label for="color">{{ trans('landingpages::global.color') }}</label>

        <input type="hidden" id="btn_color">
        <div id="btn_color_frame_holder"></div>

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

  var text = ($el.hasClass('ladda-button')) ? $el.find('.ladda-label').html(): $el.html();

  $('#text').val(text);

<?php if (! $submit) { ?>
  $('#target').val($el.attr('target')).trigger('change.select2');

  var url = $el.attr('href');
  $('#url').val(url);

  if (url != '') {
    updateImagePreview($('#select_url'));
  }
<?php } ?>

<?php if ($color) { ?>

  var color_class = '';
  var lfArrBtnClasses = window.parent.lfArrBtnClasses;

  for (var i = 0, len = lfArrBtnClasses.length; i < len; i++) {
    if ($el.hasClass(lfArrBtnClasses[i])) {
      color_class = lfArrBtnClasses[i];
      break;
    }
  }

  $('#btn_color_frame_holder').html('<iframe seamless="1" id="btn_color_frame" frameborder="0" src="{{ url('landingpages/editor/picker/button?input_id=btn_color') }}&selected=' + color_class + '" style="width:100%;height:0"></iframe>');

  $('#btn_color').val(color_class);

<?php } ?>

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

    if ($el.hasClass('ladda-button')) {
      $el.find('.ladda-label').html($('#text').val());
    } else {
      $el.html($('#text').val());
    }

<?php if (! $submit) { ?>
    $el.attr('href', $('#url').val());
    $el.attr('target', $('#target').val());
<?php } ?>

<?php if ($color) { ?>

  $el.removeClass(window.parent.lfBtnClasses);
  $el.addClass($('#btn_color').val());

<?php } ?>

      // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection