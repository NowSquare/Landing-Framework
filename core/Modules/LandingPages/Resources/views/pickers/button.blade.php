@extends('landingpages::layouts.picker')

@section('content')
<div id="btn-color-picker"></div>

@endsection

@section('script')
<style type="text/css">
  html, body {
    overflow: hidden;
  }
  .select-holder {
    float: left;
    margin: 0 2px 2px 0;
    padding: 4px;
  }
  .select-holder .inside {
    display: none;
  }
  .select-holder:hover .inside {
    display: block;
  }
  .select-holder a {
    float: left;
    width: 28px;
    height: 28px;
  }
  .select-holder.selected {
    border: 2px solid #000;
    border-radius: 4px;
    margin: -1px 0px 1px 0;
    padding: 3px 3px 3px;
  }
</style>
<script>
$(function() {

<?php /* ----------------------------------------------------------------------------
Generate button color picker
*/ ?>

  var btnColorPicker = '';
  for (var i = 0, len = lfColors.length; i < len; i++) {
    var selected = ('btn-' + lfColors[i] == '{{ $selected }}') ? ' selected' : '';
    btnColorPicker += '<div class="select-holder ' + selected + '"><a href="javascript:void(0);" title="' + lfColors[i] + '" data-class="btn-' + lfColors[i] + '" class="color-sample btn btn-sm btn-' + lfColors[i] + '"><span class="inside">&#10004;</span></a></div>';
  }

  for (var i = 0, len = lfColors.length; i < len; i++) {
    var selected = ('btn-outline-' + lfColors[i] == '{{ $selected }}') ? ' selected' : '';
    btnColorPicker += '<div class="select-holder ' + selected + '"><a href="javascript:void(0);" title="' + lfColors[i] + ' outline" data-class="btn-outline-' + lfColors[i] + '" class="color-sample btn btn-sm btn-outline-' + lfColors[i] + '"><span class="inside">&#10004;</span></a></div>';
  }

  $('#btn-color-picker').html(btnColorPicker);

<?php /* ----------------------------------------------------------------------------
Resize parent iframe
*/ ?>

  parent.$('#{{ $input_id }}_frame').height(parseInt($('html').height()));

  $(parent.window).resize(function() {
    parent.$('#{{ $input_id }}_frame').height(parseInt($('html').height()));
  });

<?php /* ----------------------------------------------------------------------------
Listen for clicks and set value
*/ ?>

  $('body').on('click', '.color-sample', function() {
    var selected = $(this).attr('data-class');
    $('.select-holder').removeClass('selected');
    $(this).parent('.select-holder').addClass('selected');
    parent.$('#{{ $input_id }}').val(selected);
  });
});
</script>
@endsection