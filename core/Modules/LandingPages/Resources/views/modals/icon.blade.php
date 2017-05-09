@extends('landingpages::layouts.modal')

@section('content') 

<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.icon') }}</h1>
  </div>
  <div class="row">
    <div class="col-xs-12">



      <div class="input-group input-group-lg">
        <input data-placement="bottomRight" class="form-control icon-picker" value="" type="text" />
        <span class="input-group-addon iconpicker-component"></span>
      </div>
<?php /*
      <div class="form-group">
          <input type="text" class="form-control" id="alt" name="alt" autocomplete="off" value="">
          <button type="button" class="btn btn-block btn-lg btn-default icon-picker iconpicker-component" data-toggle="dropdown"><i class="fa"></i></button>
      </div>
*/ ?>

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

  var icon = $el.attr('class');
  icon = (typeof icon !== typeof undefined && icon !== false) ? icon : '';
  icon = icon.replace('fa ', '');

  $('.icon-picker').iconpicker({
    selected: icon,
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
      search: '',
      iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
      iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
    }
  });

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>


  var icon = $el.attr('class');
  icon = icon.replace('fa ', '');

  // Changes detected
  window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });
});
</script>
@endsection