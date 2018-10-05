@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.design') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <div class="form-group">
            <label for="name">{{ trans('forms::global.form_style') }}</label>
              <select class="form-control" id="style" name="style">
                <option value="">{{ trans('forms::global.normal') }}</option>
                <option value="form-rounded">{{ trans('forms::global.rounded_shadow') }}</option>
              </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="bg_img">{{ trans('landingpages::global.image') }}</label>
        <div class="input-group">
          <input type="text" class="form-control" id="bg_img" name="bg_img" autocomplete="off" value="">
          <div class="input-group-btn add-on">
            <button type="button" class="btn btn-primary" id="select_bg_img" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="bg_img" data-preview="bg_img-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
            <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="bg_img-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="bg_color">{{ trans('landingpages::global.color') }}</label>
        <div class="colorpicker-hex input-group colorpicker-element colorpicker-component">
          <input type="text" id="bg_color" value="" class="form-control">
          <span class="input-group-btn add-on">
            <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
              <i style="background-color: rgb(255, 255, 255);height:32px;width:32px"></i>
            </button>
          </span>
        </div>
      </div>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
        <button type="button" class="btn btn-primary btn-material onClickUpdate">{{ trans('global.save') }}</button>
      </div>

    </div>

  </div>
</div>
@endsection

@section('script')
<script>
$(function() {
  var $colorpicker = $('.colorpicker-hex').colorpicker({
    format: 'hex'
  });

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

  var $el = $('form.ajax', window.parent.document);

  if ($el.hasClass('form-rounded')) {
    $('#style').val('form-rounded');
  } else {
    $('#style').val('');
  }

  var $body = $('body', window.parent.document);

  var bg_img = $body.css('background-image');
  if (bg_img == 'none') bg_img = '';
  bg_img = bg_img.replace(/^url\(['"]?(.+?)['"]?\)/,'$1');

  $('#bg_img').val(bg_img);

  if (bg_img != '') {
    updateImagePreview($('#select_bg_img'));
  }

  var bg_color = $body.css('background-color');
  $('#bg_color').val(bg_color);
  $colorpicker.colorpicker('setValue', bg_color);

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

    // Remove all classes
    $el.removeClass('form-rounded');

    $el.addClass($('#style').val());

    var bg_img = ($('#bg_img').val() == '' || $('#bg_img').val() == 'none') ? 'none' : 'url("' + $('#bg_img').val() + '")';
    $body.css('background-image', bg_img);

    $body.css('background-color', $('#bg_color').val());

    // Changes detected
    window.parent.lfSetPageIsDirty();
    window.parent.lfCloseModal();
  });
});
</script>
@endsection