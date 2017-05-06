@extends('landingpages::layouts.modal')

@section('content') 
<a href="javascript:void(0);" class="btn-close onClickClose"></a>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h1>{{ trans('landingpages::global.link') }}</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <div class="form-group">
        <label for="text">{{ trans('landingpages::global.text') }}</label>
          <input type="text" class="form-control" id="text" name="text" autocomplete="off" value="">
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

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  $('#text').val($el.html());
  $('#url').val($el.attr('href'));
  $('#target').val($el.attr('target'));

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

  $el.html($('#text').val());
  $el.attr('href', $('#url').val());
  $el.attr('target', $('#target').val());

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection