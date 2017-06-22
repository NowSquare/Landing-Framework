@extends('landingpages::layouts.modal')

@section('content') 

<script src="{{ url('assets/js/material-icons.min.js') }}"></script>

<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.form') }}</h1>
  </div>
  <div class="row">
    <div class="col-xs-12">

      <table class="table table-list" id="tbl-form">

        <tbody style="border: 1px solid #f3f3f3 !important">
        </tbody>

        <tfoot style="border: 1px solid #f3f3f3 !important">
          <tr>
            <td colspan="5">
              <button type="button" class="btn btn-lg btn-block btn-success add_item"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('global.add') }}</button>
            </td>
          </tr>
        </tfoot>
        
      </table>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.cancel') }}</button>
        <button type="button" class="btn btn-primary btn-material onClickUpdate">{{ trans('global.update') }}</button>
      </div>

    </div>
  </div>
</div>
<style type="text/css">
.options-closed {
  padding: 0 !important;
  border: 0 !important;
  background-color: transparent !important;
  box-shadow: none !important;
}
.options-closed .hide-when-closed {
  display: none;
}
.options-closed .full-width {
  width: 100% !important;
}
.well {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}
.show-when-hidden {
  display: none;
}
.options-closed .show-when-hidden {
  display: block;
}

.no-placeholder .placeholder-holder {
  display: none;
}
.no-placeholder .label-holder {
  width: 100%;
}

.no-size .size-holder {
  display: none;
}
.no-size .required-holder {
  width: 100%;
}

.no-options .options-holder {
  display: none;
}/*
.no-options .size-holder .form-group,
.no-options .required-holder .form-group {
  margin-bottom: 0;
}*/
</style>
<script id="form_row" type="x-tmpl-mustache">
<tr data-i="@{{ i }}" id="row@{{ i }}">
  <td style="width:49px">
    <div class="order-handle">
      <i class="material-icons">&#xE5D2;</i>
    </div>
  </td>
  <td style="width:250px">
    <select class="form-control input-lg" name="name" id="name@{{ i }}" onchange="changeFormElement(@{{ i }}, $(this).val())" @{{#undeletable}}disabled @{{/undeletable}}>
<?php
echo '{{#undeletable}}';
echo '<option value="email" selected>' . trans('global.email') . '</option>';
echo '{{/undeletable}}';
echo '{{^undeletable}}';

foreach (trans('global.form_fields') as $category => $items) {
  $category_translation = trans('global.' . $category);
  echo '<optgroup label="' . $category_translation . '">';
  foreach ($items as $item => $translation) {
    echo '<option value="' . $category . '_' . $item . '"{{#name=' . $category . '_' . $item . '}} selected {{/name=' . $category . '_' . $item . '}}>' . $category_translation . ' - ' . $translation . '</option>';
  }
}
echo '{{/undeletable}}';
?>
    </select>
  </td>
  <td>
    <div class="well well-sm options-closed 
      @{{#has_options=0}}no-options@{{/has_options=0}} 
      @{{#has_placeholder=0}}no-placeholder@{{/has_placeholder=0}}
      @{{#has_size=0}}no-size@{{/has_size=0}}
      " 
      id="options-panel@{{ i }}">

      <input type="text" class="form-control input-lg show-when-hidden" id="reference@{{ i }}" name="reference" autocomplete="off" value="@{{ reference }}"
      @{{#reference_type=label}} onkeyup="$('#label@{{ i }}').val($(this).val());" @{{/reference_type=label}}
      @{{#reference_type=placeholder}} onkeyup="$('#placeholder@{{ i }}').val($(this).val());" @{{/reference_type=placeholder}}
      >

      <div class="row hide-when-closed">
        <div class="col-xs-6 label-holder">

          <div class="form-group">
            <label class="hide-when-closed">{{ trans('landingpages::global.label') }}</label>
            <input type="text" class="form-control input-lg" id="label@{{ i }}" name="label" autocomplete="off" value="@{{ label }}"
            @{{#reference_type=label}} onkeyup="$('#reference@{{ i }}').val($(this).val());" @{{/reference_type=label}}
            >
          </div>

        </div>
        <div class="col-xs-6 placeholder-holder">

          <div class="form-group">
            <label>{{ trans('landingpages::global.placeholder') }}</label>
            <input type="text" class="form-control input-lg" id="placeholder@{{ i }}" name="placeholder" autocomplete="off" value="@{{ placeholder }}"
            @{{#reference_type=placeholder}} onkeyup="$('#reference@{{ i }}').val($(this).val());" @{{/reference_type=placeholder}}
            >
          </div>

        </div>
      </div>

      <div class="row hide-when-closed">
        <div class="col-xs-6 size-holder">

          <div class="form-group">
            <select name="size" id="size@{{ i }}" class="form-control input-lg">
              <option value="1" @{{#size=1}}selected@{{/size=1}}>{{ trans('landingpages::global.small') }}</option>
              <option value="2" @{{#size=2}}selected@{{/size=2}}>{{ trans('landingpages::global.normal') }}</option>
              <option value="3" @{{#size=3}}selected@{{/size=3}}>{{ trans('landingpages::global.large') }}</option>
            </select>
          </div>

        </div>
        <div class="col-xs-6 required-holder">

          <div class="form-group">
            <div class="checkbox">
              <input type="checkbox" name="required" id="required@{{ i }}" value="1" @{{#required=1}}checked@{{/required=1}} @{{#undeletable=1}}disabled@{{/undeletable=1}}><label for="required@{{ i }}"> {{ trans('landingpages::global.required') }}</label>
            </div>
          </div>

        </div>
      </div>

      <div class="row hide-when-closed options-holder">
        <div class="col-xs-12">

          <div class="form-group">
            <label>{{ trans('global.options') }}</label>
            <textarea class="form-control input-lg" rows="4" id="options@{{ i }}" name="options" placeholder="{{ trans('landingpages::global.form_options_placeholder') }}">@{{ options }}</textarea>
            <small class="text-muted">{{ trans('landingpages::global.form_options_help') }}</small>
          </div>

        </div>
      </div>
    
    </div>
  </td>
  <td class="text-center" style="width:50px">
    <button type="button" class="btn btn-lg btn-info btn-settings" data-toggle="tooltip" title="{{ trans('global.settings') }}" onclick="$('#options-panel@{{ i }}').toggleClass('options-closed')"><i class="mi settings"></i></button>
  </td>
  <td align="right" style="width:50px">
    <button type="button" class="btn btn-lg btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" title="{{ trans('global.delete') }}" style="margin-top:1px;" @{{#undeletable=1}}disabled@{{/undeletable=1}}><i class="mi delete"></i></button>
  </td>
</tr>
</script>

@endsection

@section('script')
<script>
$(function() {
<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  var i = 0;

  // Parse template for speed optimization
  // Initialize before inserting existing rows
  var form_row = $('#form_row').html();
  Mustache.parse(form_row);

  var formElement = new lfFormElementGenerator();

  $el.find('.form-group').each(function (j) {
    var data = {};

    var $formGroup = $(this);
    var $formControl = $formGroup.find('.form-control');
    var tagName = (typeof $formControl.prop('tagName') !== 'undefined') ? $formControl.prop('tagName').toLowerCase() : '';

    var required = $formControl.attr('required');
    required = (typeof required !== typeof undefined && required !== false) ? 1 : 0;

    var name = $formControl.attr('name');
    var label = $formGroup.find('label').html();
    label = (typeof label !== typeof undefined && label !== false) ? label : '';

    var placeholder = $formControl.attr('placeholder');
    placeholder = (typeof placeholder !== typeof undefined && placeholder !== false) ? placeholder : '';

    var reference_type = (label != '') ? 'label' : 'placeholder';
    var reference = (label != '') ? label : placeholder;

    if (typeof name !== 'undefined') {
      var undeletable = (name == 'email') ? 1 : 0;
      if (name == 'email') required = 1;
    } else if ($formGroup.find('input[type=radio]').length) {
      name = $formGroup.find('input[type=radio]').attr('name');
      required = $formGroup.find('input[type=radio]').attr('required');
      required = (typeof required !== typeof undefined && required !== false) ? 1 : 0;
    } else if ($formGroup.find('input[type=checkbox]').length) {
      name = $formGroup.find('input[type=checkbox]').attr('name');
      required = $formGroup.find('input[type=checkbox]').attr('required');
      required = (typeof required !== typeof undefined && required !== false) ? 1 : 0;
    } else {
      console.log('not found');
      name = '';
    }

    name = name.replace('[]', '');

    formElement.setType(name);

    var size = 2;
    if ($formControl.hasClass('form-control-sm')) size = 1;
    if ($formControl.hasClass('form-control-lg')) size = 3;

    i = j;

    data.i = j;
    data.undeletable = undeletable;
    data.name = name;
    data.reference = reference;
    data.reference_type = reference_type;
    data.required = required;
    data.label = label;
    data.placeholder = placeholder;
    data.size = size;
    data.options = formElement.optionsToText($formGroup);
    data.has_options = (formElement.hasOptions) ? 1 : 0;
    data.has_placeholder = (formElement.hasPlaceholder) ? 1 : 0;
    data.has_size = (formElement.hasSize) ? 1 : 0;

    addRepeaterRow('insert', data);
  });

  i++;

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

    // Make empty before inserting new
    $el.find('.form-group').remove();

    var formElement = new lfFormElementGenerator();

    $('#tbl-form tbody tr').each(function (i) {
      var $row = $(this);

      var type = $row.find('[name=name]').val();
      var label = $row.find('[name=label]').val();
      var placeholder = $row.find('[name=placeholder]').val();

      if (label != '' || placeholder != '') {
        formElement.setType(type);

        formElement.label = label;
        formElement.placeholder = placeholder;
        formElement.size = $row.find('[name=size]').val();
        formElement.required = ($row.find('[name=required]').is(':checked')) ? 1 : 0;
        formElement.options = $row.find('[name=options]').val();

        var new_item_html = formElement.getHtml();

        $(new_item_html).insertBefore($el.find('button[type=submit]'))/*.after("\r\n")*/;
      }
    });

    // Remove whitespace
    $el.contents().filter(function() {
      return this.nodeType = Node.TEXT_NODE && /\S/.test(this.nodeValue) === false;
    }).remove();
    
    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.updateAjaxForms();
    window.parent.lfCloseModal();

  });

<?php /* ----------------------------------------------------------------------------
List template
*/ ?>

  $('#tbl-form tbody').sortable({
    handle: '.order-handle',
    placeholder: {
      element: function(currentItem) {
        return $('<tr class="el-placeholder"><td colspan="5"></td></tr>')[0];
      },
      update: function(container, p) {
        return;
      }
    },
    helper: function(e, tr) {
      var $originals = tr.children();
      var $helper = tr.clone();
      $helper.addClass('el-dragging');
      $helper.children().each(function(index) {
        $(this).width(parseInt($originals.eq(index).width()) + 21);
        $(this).height($originals.eq(index).height());
      });
      return $helper;
    }
  });

  $('.add_item').on('click', function() {
    addRepeaterRow('new', null);
  });

  function addRepeaterRow(action, data) {
    if(action == 'update') {

      var html = Mustache.render(form_row, mustacheBuildOptions({
        undeletable: data.undeletable,
        name: data.name,
        reference: data.reference,
        reference_type: data.reference_type,
        label: data.label,
        required: data.required,
        placeholder: data.placeholder,
        size: data.size,
        options: data.options,
        has_options: data.has_options,
        has_placeholder: data.has_placeholder,
        has_size: data.has_size
      }));

      $('tbl-form #row' + data.i).replaceWith(html);

    } else if(action == 'new') {

      var html = Mustache.render(form_row, mustacheBuildOptions({
        i: i++,
        undeletable: '',
        name: 'general_text',
        reference: '',
        reference_type: 'label',
        label: '',
        required: 0,
        placeholder: '',
        size: 2,
        options: '',
        has_options: 0,
        has_placeholder: 1,
        has_size: 1
      }));

      $('#tbl-form tbody').append(html);
      rowBindings(i - 1);

    } else if (action == 'insert'){

      var html = Mustache.render(form_row, mustacheBuildOptions({
        i: data.i,
        undeletable: data.undeletable,
        name: data.name,
        reference: data.reference,
        reference_type: data.reference_type,
        label: data.label,
        required: data.required,
        placeholder: data.placeholder,
        size: data.size,
        options: data.options,
        has_options: data.has_options,
        has_placeholder: data.has_placeholder,
        has_size: data.has_size
      }));

      $('#tbl-form tbody').append(html);
      rowBindings(data.i);
    }
  }

  $('#tbl-form').on('click', '.btn-delete', function() {
    $(this).parents('tr').remove();
  });
});

function rowBindings(i) {
  $('table#tbl-form > tbody > tr').not('.binded').each(function() {
    var $tr = $(this);
    $tr.addClass('binded');

    bsTooltipsPopovers();
  });
}

function changeFormElement(i, name) {
  var formElement = new lfFormElementGenerator();

  formElement.setType(name);

  var $well = $('table#tbl-form > tbody > tr#row' + i + '').find('.well');

  if (formElement.hasOptions) {
    $well.removeClass('no-options');
  } else {
    $well.addClass('no-options');
  }

  if (formElement.hasPlaceholder) {
    $well.removeClass('no-placeholder');
  } else {
    $well.addClass('no-placeholder');
  }

  if (formElement.hasSize) {
    $well.removeClass('no-size');
  } else {
    $well.addClass('no-size');
  }
}

function lfFormElementGenerator() {

  this.count = 1;
  this.label = '';
  this.placeholder = '';
  this.size = 2;
  this.required = 0;
  this.options = '';

  var TAB = "\t";
  var CRLF = "\r\n";

  this.setType = function(type) {
    this.type = type;

    switch (this.type) {
      case 'general_select':
        this.hasPlaceholder = false;
        this.hasSize = true;
        this.hasOptions = true;
        break;

      case 'general_radios': 
      case 'general_multiple_choice': 
        this.hasPlaceholder = false;
        this.hasSize = false;
        this.hasOptions = true;
        break;

      case 'personal_gender':
      case 'personal_title': 
        this.hasPlaceholder = false;
        this.hasSize = true;
        this.hasOptions = false;
        break;

      case 'general_checkbox': 
        this.hasPlaceholder = false;
        this.hasSize = false;
        this.hasOptions = false;
        break;

      default:
        this.hasPlaceholder = true;
        this.hasSize = true;
        this.hasOptions = false;
    }
  }

  this.getType = function() {
    return this.type;
  }

  this.optionsToText = function($formGroup) {
    var options = '';

    // Select
    if ($formGroup.find('select').length) {
      var $select_options = $formGroup.find('select.form-control option');

      $select_options.each(function (i) {
        var selected = $(this).attr('selected');
        selected = (typeof selected !== typeof undefined && selected !== false) ? '[x] ' : '';

        options += selected + $(this).text();
        if ($select_options.length > i + 1) options += CRLF;
      });
    }

    // Radio buttons
    if ($formGroup.find('input[type=radio]').length) {
      var $radio_options = $formGroup.find('.form-check');

      $radio_options.each(function (i) {
        var checked = $(this).find('input[type=radio]').attr('checked');
        checked = (typeof checked !== typeof undefined && checked !== false) ? '[x] ' : '';

        options += checked + $(this).find('input[type=radio]').val();
        if ($radio_options.length > i + 1) options += CRLF;
      });
    }

    // Checkboxes
    if ($formGroup.find('input[type=checkbox]').length) {
      var $check_options = $formGroup.find('.form-check');

      $check_options.each(function (i) {
        var checked = $(this).find('input[type=checkbox]').attr('checked');
        checked = (typeof checked !== typeof undefined && checked !== false) ? '[x] ' : '';

        options += checked + $(this).find('input[type=checkbox]').val();
        if ($check_options.length > i + 1) options += CRLF;
      });
    }

    return options;
  }

  this.optionsToArray = function(options) {
    if (typeof options !== 'undefined') {
      var options = options.split(/\n/);
      var result = [];
      for (var i = 0; i < options.length; i++) {
        var text = options[i];
        var selected = false;
        if (text.substr(0, 3) == '[x]') {
          text = $.trim(text.substr(3, text.length));
          selected = true;
        }

        result[i] = {
          text: text,
          selected: selected
        };
      }
      return result;
    } else {
      return [];
    }
  }

  this.getHtml = function() {

    var html = '';
    var html_size;
    var html_required = (parseInt(this.required) == 1) ? ' required' : '';
    var id = this.type;
    var name = this.type;

    if (name.indexOf('general_') >= 0) {
      name += '[]';
    }

    switch (parseInt(this.size)) {
      case 1: html_size = ' form-control-sm'; break;
      case 3: html_size = ' form-control-lg'; break;
      default: html_size = '';
    }

    switch (this.type) {

      case 'general_textarea':

        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;
        html += TAB + TAB + '<textarea class="form-control' + html_size + '" id="' + id + '-' + this.count + '" name="' + name + '" placeholder="' + this.placeholder + '"' + html_required + ' rows="4"></textarea>' + CRLF;
        html += TAB + '</div>' + CRLF;

        this.count ++;
        break;

      case 'general_select':
        var options = this.optionsToArray(this.options);

        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;
        html += TAB + TAB + '<select class="form-control' + html_size + '" id="' + id + '-' + this.count + '" name="' + name + '" placeholder="' + this.placeholder + '"' + html_required + '>' + CRLF;

        if (options.length > 0) {
          for (var i = 0; i < options.length; i++) {
            var selected = (options[i].selected) ? ' selected' : '';
            html += TAB + TAB + TAB + '<option value="' + options[i].text + '"' + selected + '>' + options[i].text + '</option>' + CRLF;
          }
        }

        html += TAB + TAB + '</select>' + CRLF;
        html += TAB + '</div>' + CRLF;

        this.count ++;

        break;

      case 'general_radios': 
        var options = this.optionsToArray(this.options);

        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;

        if (options.length > 0) {
          for (var i = 0; i < options.length; i++) {
            var selected = (options[i].selected) ? ' checked' : '';
            html += TAB + TAB + '<div class="form-check">' + CRLF;
            html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
            html += TAB + TAB + TAB + TAB + '<input type="radio" class="form-check-input" name="' + name + '" id="' + id + '-' + i + '" value="' + options[i].text + '"' + selected + '' + html_required + '>' + CRLF;
            html += TAB + TAB + TAB + TAB + options[i].text + CRLF;
            html += TAB + TAB + TAB + '</label>' + CRLF;
            html += TAB + TAB + '</div>' + CRLF;
          }
        }

        html += TAB + '</div>' + CRLF;

        break;

      case 'personal_gender': 
        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;

        html += TAB + TAB + '<div class="form-check">' + CRLF;
        html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
        html += TAB + TAB + TAB + TAB + '<input type="radio" class="form-check-input" name="' + name + '" id="' + id + '-0" value="0"' + html_required + '>' + CRLF;
        html += TAB + TAB + TAB + TAB + "{{ trans('global.form_fields_gender.male') }}" + CRLF;
        html += TAB + TAB + TAB + '</label>' + CRLF;
        html += TAB + TAB + '</div>' + CRLF;

        html += TAB + TAB + '<div class="form-check">' + CRLF;
        html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
        html += TAB + TAB + TAB + TAB + '<input type="radio" class="form-check-input" name="' + name + '" id="' + id + '-1" value="1"' + html_required + '>' + CRLF;
        html += TAB + TAB + TAB + TAB + "{{ trans('global.form_fields_gender.female') }}" + CRLF;
        html += TAB + TAB + TAB + '</label>' + CRLF;
        html += TAB + TAB + '</div>' + CRLF;

        html += TAB + '</div>' + CRLF;

        break;

      case 'personal_title': 
        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;

        html += TAB + TAB + '<div class="form-check">' + CRLF;
        html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
        html += TAB + TAB + TAB + TAB + '<input type="radio" class="form-check-input" name="' + name + '" id="' + id + '-0" value="0"' + html_required + '>' + CRLF;
        html += TAB + TAB + TAB + TAB + "{{ trans('global.form_fields_title.mr') }}" + CRLF;
        html += TAB + TAB + TAB + '</label>' + CRLF;
        html += TAB + TAB + '</div>' + CRLF;

        html += TAB + TAB + '<div class="form-check">' + CRLF;
        html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
        html += TAB + TAB + TAB + TAB + '<input type="radio" class="form-check-input" name="' + name + '" id="' + id + '-1" value="1"' + html_required + '>' + CRLF;
        html += TAB + TAB + TAB + TAB + "{{ trans('global.form_fields_title.ms') }}" + CRLF;
        html += TAB + TAB + TAB + '</label>' + CRLF;
        html += TAB + TAB + '</div>' + CRLF;

        html += TAB + '</div>' + CRLF;

        break;

      case 'general_multiple_choice': 
        var options = this.optionsToArray(this.options);

        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;

        if (options.length > 0) {
          for (var i = 0; i < options.length; i++) {
            var selected = (options[i].selected) ? ' checked' : '';
            html += TAB + TAB + '<div class="form-check">' + CRLF;
            html += TAB + TAB + TAB + '<label class="form-check-label">' + CRLF;
            html += TAB + TAB + TAB + TAB + '<input type="checkbox" class="form-check-input" name="' + name + '" id="' + id + '-' + i + '" value="' + options[i].text + '"' + selected + '' + html_required + '>' + CRLF;
            html += TAB + TAB + TAB + TAB + options[i].text + CRLF;
            html += TAB + TAB + TAB + '</label>' + CRLF;
            html += TAB + TAB + '</div>' + CRLF;
          }
        }

        html += TAB + '</div>' + CRLF;

        break;

      case 'personal_gender':
      case 'personal_title': 
        break;

      case 'general_checkbox': 
        break;

      default:
        var html_type;

        switch (this.type) {
          case 'general_number': 
            html_type = 'number'; 
            break;

          case 'general_url': 
            html_type = 'url'; 
            break;

          case 'business_email': 
          case 'email': 
            html_type = 'email'; 
            break;

          case 'personal_phone': 
          case 'personal_mobile': 
          case 'personal_fax': 
          case 'business_phone': 
          case 'business_mobile': 
          case 'business_fax': 
            html_type = 'tel'; 
            break;

          case 'general_date':
          case 'personal_birthday':
          case 'booking_date':
          case 'booking_start_date':
          case 'booking_end_date': 
            html_type = 'date'; 
            break;

          case 'general_time':
          case 'booking_time':
          case 'booking_start_time':
          case 'booking_end_time': 
            html_type = 'time'; 
            break;

          case 'general_date_time':
          case 'booking_date_time':
          case 'booking_date_start_time':
          case 'booking_date_end_time': 
            html_type = 'datetime-local'; 
            break;

          default: 
            html_type = 'text';
        }

        html += TAB + '<div class="form-group">' + CRLF;
        if (this.label != '') html += TAB + TAB + '<label for="' + id + '">' + this.label + '</label>' + CRLF;
        html += TAB + TAB + '<input type="' + html_type + '" class="form-control' + html_size + '" id="' + id + '" name="' + name + '" placeholder="' + this.placeholder + '"' + html_required + '>' + CRLF;
        html += TAB + '</div>' + CRLF;
    }

    return html;
  }
}

</script>
@endsection