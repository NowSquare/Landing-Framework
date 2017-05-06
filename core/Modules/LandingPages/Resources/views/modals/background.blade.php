@extends('landingpages::layouts.modal')

@section('content') 
<a href="javascript:void(0);" class="btn-close onClickClose"></a>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h1>{{ trans('landingpages::global.background') }}</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10 col-sm-6">

<?php if ($bg_img) { ?>
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
<?php } ?>

<?php if ($bg_color) { ?>
      <div class="form-group">
        <label for="bg_color">{{ trans('landingpages::global.color') }}</label>
        <div class="colorpicker-rgba input-group colorpicker-element colorpicker-component">
          <input type="text" id="bg_color" value="" class="form-control">
          <span class="input-group-btn add-on">
            <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
              <i style="background-color: rgb(255, 255, 255);height:32px;width:32px"></i>
            </button>
          </span>
        </div>
      </div>
<?php } ?>

<?php if ($bg_gradient) { ?>

      <div class="form-group">
        <label for="bg_gradient_start">{{ trans('landingpages::global.gradient') }}</label>
      </div>

      <div class="row">
        <div class="col-xs-6">

          <div class="form-group">
            <div class="colorpicker-gradient-start input-group colorpicker-element colorpicker-component">
              <input type="text" id="bg_gradient_start" value="" class="form-control">
              <span class="input-group-btn add-on">
                <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                  <i style="background-color: rgb(255, 255, 255);height:32px;width:32px"></i>
                </button>
              </span>
            </div>
          </div>

        </div>
        <div class="col-xs-6">

          <div class="form-group">
            <div class="colorpicker-gradient-end input-group colorpicker-element colorpicker-component">
              <input type="text" id="bg_gradient_end" value="" class="form-control">
              <span class="input-group-btn add-on">
                <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                  <i style="background-color: rgb(255, 255, 255);height:32px;width:32px"></i>
                </button>
              </span>
            </div>
          </div>

        </div>
      </div>

<?php } ?>

      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.cancel') }}</button>
      <button type="button" class="btn btn-primary btn-material onClickUpdate">{{ trans('global.update') }}</button>

    </div>
  </div>
</div>
@endsection

@section('script') 
<script>
$(function() {
  var $colorpicker = $('.colorpicker-rgba').colorpicker({
    format: 'rgba'
  });

  var $colorpicker_gradient_start = $('.colorpicker-gradient-start').colorpicker({
    format: 'hex'
  });

  var $colorpicker_gradient_end = $('.colorpicker-gradient-end').colorpicker({
    format: 'hex'
  });

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>
  var $el = $('.{{ $el_class }}', window.parent.document);

<?php if ($bg_img) { ?>
  var bg_img = $el.find('.-x-block-bg-img').css('background-image');
  if (bg_img == 'none') bg_img = '';
  bg_img = bg_img.replace(/^url\(['"]?(.+?)['"]?\)/,'$1');

  $('#bg_img').val(bg_img);

  if (bg_img != '') {
    updateImagePreview($('#select_bg_img'));
  }
<?php } ?>

<?php if ($bg_color) { ?>
  var bg_color = $el.find('.-x-block-bg-color').css('background-color');
  $('#bg_color').val(bg_color);
  $colorpicker.colorpicker('setValue', bg_color);
<?php } ?>

<?php if ($bg_gradient) { ?>
  var bg_gradient = $el.find('.-x-block-bg-gradient').css('background-image');

  var bg_gradient_start = bg_gradient.split('0%, ')[1].split('100%)')[0];
  var bg_gradient_end = bg_gradient.split('0%')[0].split('linear-gradient(')[1];

  $('#bg_gradient_start').val(bg_gradient_start);
  $('#bg_gradient_end').val(bg_gradient_end);

  $colorpicker_gradient_start.colorpicker('setValue', bg_gradient_start);
  $colorpicker_gradient_end.colorpicker('setValue', bg_gradient_end);
<?php } ?>

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

<?php if ($bg_img) { ?>
    var bg_img = ($('#bg_img').val() == '' || $('#bg_img').val() == 'none') ? 'none' : 'url("' + $('#bg_img').val() + '")';
    $el.find('.-x-block-bg-img').css('background-image', bg_img);
<?php } ?>

<?php if ($bg_color) { ?>
    $el.find('.-x-block-bg-color').css('background-color', $('#bg_color').val());
<?php } ?>

<?php if ($bg_gradient) { ?>
  var bg_gradient_start = $('#bg_gradient_start').val();
  var bg_gradient_end = $('#bg_gradient_end').val();

  var bgWebKit = '-webkit-linear-gradient(top,  ' + bg_gradient_start + ' 0%, ' + bg_gradient_end + ' 100%)';
  var bgMoz = '-moz-linear-gradient(top, ' + bg_gradient_start + ' 0%, ' + bg_gradient_end + ' 100%)';

  $el.find('.-x-block-bg-gradient')
    .css('background-image', bgWebKit)
    .css('background-image', bgMoz);
<?php } ?>

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection