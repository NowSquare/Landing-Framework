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
        <thead>
          <tr>
            <th style="width:49px"></th>
            <th>{{ trans('landingpages::global.name') }}</th>
            <th>{{ trans('landingpages::global.type') }}</th>
            <th>{{ trans('landingpages::global.settings') }}</th>
            <th style="width:50px"></th>
          </tr>
        </thead>

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

<script id="form_row" type="x-tmpl-mustache">
<tr data-i="@{{ i }}" id="row@{{ i }}">
  <td>
    <div class="order-handle">
      <i class="material-icons">&#xE5D2;</i>
    </div>
  </td>
  <td>
    <input type="text" class="form-control input-lg" id="title@{{ i }}" name="title" autocomplete="off" value="@{{ title }}">
  </td>
  <td>
    <select class="form-control input-lg">
      <optgroup label="Generic">
        <option>Text</option>
        <option>Textarea</option>
        <option>Multiple choice</option>
      </optgroup>
      <optgroup label="Variables">
        <option>First name</option>
        <option>Last name</option>
        <option>Full name</option>
        <option>Address</option>
        <option>Street name</option>
        <option>Street number</option>
        <option>Phone</option>
        <option>Mobile</option>
        <option>Zip</option>
        <option>City</option>
        <option>Region</option>
        <option>Country</option>
        <option>Birthday</option>
        <option>Start date</option>
        <option>End date</option>
        <option>Start date/time</option>
        <option>End date/time</option>
      </optgroup>
    </select>
  </td>
  <td>
    Settings
  </td>
  <td align="right">
    <button type="button" class="btn btn-lg btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" title="{{ trans('global.delete') }}" style="margin-top:1px;"><i class="fa fa-times"></i></button>
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


  $el.find('.form-group').each(function (j) {
    var $row = $(this);
    var data = {};

    var title = $row.attr('title');
    title = (typeof title !== typeof undefined && title !== false) ? title : '';
    var url = $row.attr('href');
    url = (typeof url !== typeof undefined && url !== false) ? url : '';

    i = j;

    data.i = j;
    data.title = title;
    data.url = url;

    addRepeaterRow('insert', data);
  });

  i++;

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>


    // Get one element for cloning, remove class
    var $item = $el.find('a').first()

    var icon = $item.find('i').attr('class');
    icon = icon.replace('fa ', '');
    $item.find('i').removeClass(icon);
    $item = $item.clone();

    // Make empty before inserting new
    $el.html('');

    $('#tbl-form tbody tr').each(function (i) {
      var $row = $(this);

      var icon = $row.find('.icon-picker i').attr('class');
      icon = icon.replace('iconpicker-component', '');
      var title = $row.find('[name=title]').val();
      var url = $row.find('[name=url]').val();

      var $new_item = $item.clone();

      // Get font vendor
      var new_icon = parent.lfExtractIconClass($new_item.find('i').attr('class'));

      $new_item.find('i').removeClass(new_icon.font);
      $new_item.find('i').addClass(icon);
      $new_item.attr('title', title);
      $new_item.attr('href', url);

      $el.append($new_item);
    });

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

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
    if(action == 'new') {

      var html = Mustache.render(form_row, mustacheBuildOptions({
        i: i++,
        title: '',
        url: '#'
      }));

      $('#tbl-form tbody').append(html);
      rowBindings(i - 1);

    } else if (action == 'insert'){

      var html = Mustache.render(form_row, mustacheBuildOptions({
        i: data.i,
        title: data.title,
        url: data.url
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


  bsTooltipsPopovers();
}
</script>
@endsection