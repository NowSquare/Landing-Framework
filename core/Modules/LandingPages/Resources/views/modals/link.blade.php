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


<?php if (Gate::allows('limitation', 'forms.visible')) { ?>
      <div class="well well-sm" style="margin-bottom: 15px">
      <ul class="nav nav-tabs navtab-custom">
        <li<?php if($tab == 'url') echo ' class="active"'; ?>><a href="#tab_url" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.url') }}</a></li>
        <li<?php if($tab == 'form') echo ' class="active"'; ?>><a href="#tab_form" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.form') }}</a></li>
      </ul>
<?php } ?>

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>
      <div class="tab-content m-b-0" style="box-shadow: none">
        <div class="tab-pane<?php if($tab == 'url') echo ' active'; ?>" id="tab_url">
<?php } ?>

          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="" placeholder="http://">
              <div class="input-group-btn add-on">
                <button type="button" class="btn btn-primary" id="select_url" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="url" data-preview="url-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="url-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
              </div>
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 0">
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

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>
        </div>
        <div class="tab-pane<?php if($tab == 'form') echo ' active'; ?>" id="tab_form">

          <div class="form-group" style="margin-bottom: 0">
<?php

echo Former::select('form')
  ->addOption('&nbsp;')
  ->class('select2-required form-control')
  ->name('form')
  ->fromQuery($forms, 'name', 'local_domain')
  ->label(false);
?>
          </div>

        </div>

      </div>
      </div>
<?php } ?>

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

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>

  var form = $el.attr('data-form');

  if (typeof form !== typeof undefined && form !== false) {
    // A form is set
    var url = '';
    $('#form').val(form).trigger('change.select2');
  } else {
    // No form is set
    $('#target').val($el.attr('target')).trigger('change.select2');
    var url = $el.attr('href');
  }

<?php } else { ?>
  $('#target').val($el.attr('target')).trigger('change.select2');
  var url = $el.attr('href');

<?php } // forms.visible ?>

  $('#url').val(url);

  if (url != '') {
    updateImagePreview($('#select_url'));
  }

<?php } // ! $submit ?>

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

<?php } // $color ?>

<?php } // $el_class != '' ?>

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

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>

  var form = $('#form').val();

  if (form != '') {
    // A form is selected
    $el.attr('data-form', form);
    $el.attr('href', 'javascript:void(0);');
    $el.removeAttr('target');
  } else {
    // No form is selected
    console.log($('#url').val());
    $el.removeAttr('data-form');
    $el.attr('href', $('#url').val());

    $el.attr('target', $('#target').val());
  }

<?php } else { ?>

  $el.attr('href', $('#url').val());
  $el.attr('target', $('#target').val());

<?php } // forms.visible ?>

<?php } // ! $submit ?>

<?php if ($color) { ?>

  $el.removeClass(window.parent.lfBtnClasses);
  $el.addClass($('#btn_color').val());

<?php } // $color ?>

      // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection