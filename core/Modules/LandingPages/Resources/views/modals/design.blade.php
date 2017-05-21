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

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

  var $el = $('form.ajax', window.parent.document);

  if ($el.hasClass('form-rounded')) {
    $('#style').val('form-rounded');
  } else {
    $('#style').val('');
  }

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

    // Remove all classes
    $el.removeClass('form-rounded');

    $el.addClass($('#style').val());

    // Changes detected
    window.parent.lfSetPageIsDirty();
    window.parent.lfCloseModal();
  });
});
</script>
@endsection