@extends('landingpages::layouts.modal')

@section('content')
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.link') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <div class="form-group">
            <label for="text">{{ trans('landingpages::global.text') }}</label>
              <input type="text" class="form-control" id="text" name="text" autocomplete="off" value="">
          </div>

<?php if (! $submit) { ?>

      <ul class="nav nav-tabs navtab-custom navtab-shadow" id="link_tabs">
        <li<?php if($tab == 'url') echo ' class="active"'; ?>><a href="#tab_url" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.url') }}</a></li>
<?php if (Gate::allows('limitation', 'forms.visible')) { ?>
        <li<?php if($tab == 'form') echo ' class="active"'; ?>><a href="#tab_form" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.form') }}</a></li>
<?php } ?>
        <li<?php if($tab == 'vcard') echo ' class="active"'; ?>><a href="#tab_vcard" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.vcard') }}</a></li>
      </ul>

      <div class="tab-content navtab-shadow">
        <div class="tab-pane<?php if($tab == 'url') echo ' active'; ?>" id="tab_url">

          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="" placeholder="http://" onkeydown="$('#form').val('').trigger('change.select2');">
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

        </div>

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>
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
<?php } ?>

        <div class="tab-pane<?php if($tab == 'vcard') echo ' active'; ?>" id="tab_vcard">

          <style type="text/css">
            .navtab-sub {
              background-color: #fcfcfc;
            }
            .navtab-sub li.active a {
              background-color: #fcfcfc !important;;
            }
            .shadow-container {
              box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0), 0 3px 1px -2px rgba(0, 0, 0, 0), 0 1px 5px 0 rgba(0, 0, 0, 0.11);
            }
          </style>

          <div class="shadow-container">
            <ul class="nav nav-tabs navtab-custom navtab-sub navtab-shadow nav-justified">
              <li class="active"><a href="#tab_vcard_personal" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.personal') }}</a></li>
              <li><a href="#tab_vcard_contact" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.contact') }}</a></li>
              <li><a href="#tab_vcard_address" data-toggle="tab" aria-expanded="false">{{ trans('landingpages::global.address') }}</a></li>
            </ul>
          </div>

          <div class="tab-content navtab-shadow" style="margin: 20px 0 0 0; padding: 0; box-shadow: none">
            <div class="tab-pane active" id="tab_vcard_personal">
            
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="vcard[prefix]" class="control-label">{{ trans('landingpages::global.prefix') }}</label>
                    <input class="form-control" id="vcard[prefix]" type="text" name="vcard[prefix]" value="">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group required">
                    <label for="vcard[first_name]" class="control-label">{{ trans('landingpages::global.first_name') }}<sup>*</sup></label>
                    <input class="form-control" required="true" id="vcard[first_name]" type="text" name="vcard[first_name]" value="">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group required">
                    <label for="vcard[last_name]" class="control-label">{{ trans('landingpages::global.last_name') }}<sup>*</sup></label>
                    <input class="form-control" required="true" id="vcard[last_name]" type="text" name="vcard[last_name]" value="">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="vcard[suffix]" class="control-label">{{ trans('landingpages::global.suffix') }}</label>
                    <input class="form-control" id="vcard[suffix]" type="text" name="vcard[suffix]" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group m-b-0">
                    <label for="vcard[company]" class="control-label">{{ trans('landingpages::global.company') }}</label>
                    <input class="form-control" id="vcard[company]" type="text" name="vcard[company]" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group m-b-0">
                    <label for="vcard[job_title]" class="control-label">{{ trans('landingpages::global.job_title') }}</label>
                    <input class="form-control" id="vcard[job_title]" type="text" name="vcard[job_title]" value="">
                  </div>
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab_vcard_contact">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="vcard[email]" class="control-label">{{ trans('landingpages::global.email') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                      <input class="form-control" id="vcard[email]" type="text" name="vcard[email]" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="vcard[phone_home]" class="control-label">{{ trans('landingpages::global.phone_home') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i></span>
                      <input class="form-control" id="vcard[phone_home]" type="text" name="vcard[phone_home]" value="">
                    </div>
                  </div>
                  <div class="form-group m-b-0">
                    <label for="vcard[personal_website]" class="control-label">{{ trans('landingpages::global.personal_website') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
                      <input class="form-control" id="vcard[personal_website]" type="text" name="vcard[personal_website]" value="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="vcard[fax]" class="control-label">{{ trans('landingpages::global.fax') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-fax"></i></span>
                      <input class="form-control" id="vcard[fax]" type="text" name="vcard[fax]" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="vcard[phone_work]" class="control-label">{{ trans('landingpages::global.phone_work') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i></span>
                      <input class="form-control" id="vcard[phone_work]" type="text" name="vcard[phone_work]" value="">
                    </div>
                  </div>
                  <div class="form-group m-b-0">
                    <label for="vcard[work_website]" class="control-label">{{ trans('landingpages::global.work_website') }}</label>
                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
                      <input class="form-control" id="vcard[work_website]" type="text" name="vcard[work_website]" value="">
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab_vcard_address">

              <div class="row">
                <div class="col-md-6">
                  <legend>{{ trans('landingpages::global.home_address') }}</legend>
                  <div class="form-group">
                    <label for="vcard[home_street]" class="control-label">{{ trans('landingpages::global.street') }}</label>
                    <input class="form-control" id="vcard[home_street]" type="text" name="vcard[home_street]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[home_city]" class="control-label">{{ trans('landingpages::global.city') }}</label>
                    <input class="form-control" id="vcard[home_city]" type="text" name="vcard[home_city]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[home_state]" class="control-label">{{ trans('landingpages::global.state_province') }}</label>
                    <input class="form-control" id="vcard[home_state]" type="text" name="vcard[home_state]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[home_zip]" class="control-label">{{ trans('landingpages::global.zip_postal') }}</label>
                    <input class="form-control" id="vcard[home_zip]" type="text" name="vcard[home_zip]" value="">
                  </div>
                  <div class="form-group m-b-0">
                    <label for="vcard[home_country]" class="control-label">{{ trans('landingpages::global.country_region') }}</label>
                    <input class="form-control" id="vcard[home_country]" type="text" name="vcard[home_country]" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <legend>{{ trans('landingpages::global.business_address') }}</legend>
                  <div class="form-group">
                    <label for="vcard[business_street]" class="control-label">{{ trans('landingpages::global.street') }}</label>
                    <input class="form-control" id="vcard[business_street]" type="text" name="vcard[business_street]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[business_city]" class="control-label">{{ trans('landingpages::global.city') }}</label>
                    <input class="form-control" id="vcard[business_city]" type="text" name="vcard[business_city]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[business_state]" class="control-label">{{ trans('landingpages::global.state_province') }}</label>
                    <input class="form-control" id="vcard[business_state]" type="text" name="vcard[business_state]" value="">
                  </div>
                  <div class="form-group">
                    <label for="vcard[business_zip]" class="control-label">{{ trans('landingpages::global.zip_postal') }}</label>
                    <input class="form-control" id="vcard[business_zip]" type="text" name="vcard[business_zip]" value="">
                  </div>
                  <div class="form-group m-b-0">
                    <label for="vcard[business_country]" class="control-label">{{ trans('landingpages::global.country_region') }}</label>
                    <input class="form-control" id="vcard[business_country]" type="text" name="vcard[business_country]" value="">
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>

<?php } ?>

        </div>
      </div>

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

  var tab = 'url'; 
  var form = $el.attr('data-form');
  var is_vcard = $el.attr('data-vcard');
  is_vcard = (typeof is_vcard !== typeof undefined && is_vcard !== false) ? true : false;

<?php if (Gate::allows('limitation', 'forms.visible')) { ?>

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

  if (is_vcard) url = '#';

  $('#url').val(url);

  if (url != '') {
    updateImagePreview($('#select_url'));
  }

  // Set vCard values
  var vcard = $el.attr('data-vcard-data');
  if (typeof vcard !== 'undefined') {
    vcard = JSON.parse(vcard);

    $.each(vcard, function(key, val) {
      $('input[name="vcard[' + key + ']"]').val(val);
    });
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

    // Selected tab
    var link_tab = $('#link_tabs li.active a').attr('href');

    if ($el.hasClass('ladda-button')) {
      $el.find('.ladda-label').html($('#text').val());
    } else {
      $el.html($('#text').val());
    }

<?php if (! $submit) { ?>

    // Save vCard data
    var vcard = {};
    $('input[name^="vcard"]').each(function() {
      var name = $(this).attr('name');
      name = name.substring(6, name.length - 1);
      vcard[name] = $(this).val();
    });

    vcard = JSON.stringify(vcard);
    $el.attr('data-vcard-data', vcard);

    // Remove attrs
    $el.removeAttr('data-form');
    $el.removeAttr('data-vcard');
    $el.removeAttr('target');
    $el.removeClass('vcard-link');
    
<?php if (Gate::allows('limitation', 'forms.visible')) { ?>

  var form = $('#form').val();

  if (link_tab == '#tab_form' && form != '') {
    // A form is selected
    $el.attr('data-form', form);
    $el.attr('href', 'javascript:void(0);');
  } else {
    // No form is selected
    if (link_tab == '#tab_url') {
      $el.attr('href', $('#url').val());
      $el.attr('target', $('#target').val());
    } else if (link_tab == '#tab_vcard') {
      $el.attr('href', '#');
      //$el.attr('href', 'javascript:vCard(this);');
      $el.attr('data-vcard', 1);
      $el.addClass('vcard-link');
    }
  }

  // Rebind modals  
  $el.off('click.form-modal');
  window.parent.$('.modal-frame').remove();
  window.parent.bindAjaxFormLinks();

<?php } else { ?>

  if (link_tab == '#tab_url') {
    $el.attr('href', $('#url').val());
    $el.attr('target', $('#target').val());
  } else if (link_tab == '#tab_vcard') {
    $el.attr('href', '#');
    //$el.attr('href', 'javascript:vCard(this);');
    $el.attr('data-vcard', 1);
    $el.addClass('vcard-link');
  }

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