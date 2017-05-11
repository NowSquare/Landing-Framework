@extends('landingpages::layouts.modal')

@section('content') 

<script src="{{ url('assets/js/material-icons.min.js') }}"></script>

<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.icon') }}</h1>
  </div>
  <div class="row">
    <div class="col-xs-8 col-sm-6">

      <div class="form-group">
        <div class="input-group input-group-lg">
          <input i class="form-control icon-picker" value="" type="text" />
          <span class="input-group-addon" id="icon"></span>
        </div>
      </div>

      <div class="form-group">
        <label for="color">{{ trans('landingpages::global.color') }}</label>
        <div class="colorpicker-icon input-group colorpicker-element colorpicker-component input-group-lg">
          <input type="text" id="color" value="" class="form-control">
          <span class="input-group-btn add-on">
            <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
              <i style="background-color: rgb(255, 255, 255);height:44px;width:44px"></i>
            </button>
          </span>
        </div>
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

  var $colorpicker_icon = $('.colorpicker-icon').colorpicker({
    format: 'hex'
  });

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  var color = $el.css('color');
  $('#color').val(color);
  $colorpicker_icon.colorpicker('setValue', color);

  var icon = parent.lfExtractIconClass($el.attr('class'));

  $('.icon-picker').iconpicker({
    selected: icon.class,
    showFooter: true,
    searchInFooter: true,
    hideOnSelect: true,
    animation: true,
    placement: 'auto',
    templates: {
      popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
        '<div class="popover-title"></div><div class="popover-content"></div></div>',
      footer: '<div class="popover-footer"></div>',
      buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">' + _lang['cancel'] + '</button>' +
        ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">' + _lang['accept'] + '</button>',
      search: '<input type="search" class="form-control iconpicker-search" placeholder="' + _lang['type_to_filter'] + '" />',
      iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
      iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
    },
    icons: $.merge(materialIcons, $.iconpicker.defaultOptions.icons),
    fullClassFormatter: function(val) {
      if(val.match(/^fa-/)) {
        return 'fa ' + val;
      } else {
        return 'mi ' + val;
      }
    }
  });

/*
fontawesome-iconpicker.js

replace:

var b = a(this);

var b = $(this);
*/

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

    $el.css('color', $('#color').val());

    var new_icon = $('#icon i').attr('class');
    $el.removeClass(icon.font);
    $el.removeClass(icon.class);
    $el.addClass(new_icon);

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection