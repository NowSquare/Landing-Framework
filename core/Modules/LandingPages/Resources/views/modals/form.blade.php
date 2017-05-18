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
            <th style="width:50px"></th>
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
    <input type="text" class="form-control input-lg" id="name@{{ i }}" name="name" autocomplete="off" value="@{{ name }}">
  </td>
  <td>
    <select class="form-control input-lg">
<?php
foreach (trans('global.form_fields') as $category => $items) {
  $category_translation = trans('global.' . $category);
  echo '<optgroup label="' . $category_translation . '">';
  foreach ($items as $item => $translation) {
    echo '<option value="' . $category . '.' . $item . '">' . $category_translation . ' - ' . $translation . '</option>';
  }
}
?>
    </select>
  </td>
  <td class="text-center">
    <button type="button" class="btn btn-lg btn-info" data-toggle="tooltip" title="{{ trans('global.settings') }}"><i class="mi settings"></i></button>
  </td>
  <td align="right">
    <button type="button" class="btn btn-lg btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" title="{{ trans('global.delete') }}" style="margin-top:1px;"><i class="mi delete"></i></button>
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

    var name = $row.find('label').html();
    name = (typeof name !== typeof undefined && name !== false) ? name : '';
    var placeholder = $row.find('input').attr('placeholder');
    placeholder = (typeof placeholder !== typeof undefined && placeholder !== false) ? placeholder : '';

    if (name == '') name = placeholder;

    var url = $row.attr('href');
    url = (typeof url !== typeof undefined && url !== false) ? url : '';

    var undeletable = true;

    i = j;

    data.i = j;
    data.undeletable = j;
    data.name = name;
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

      var name = $row.find('[name=name]').val();
      var url = $row.find('[name=url]').val();

      var $new_item = $item.clone();

      $new_item.attr('name', name);
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
        undeletable: '',
        name: '',
        url: '#'
      }));

      $('#tbl-form tbody').append(html);
      rowBindings(i - 1);

    } else if (action == 'insert'){

      var html = Mustache.render(form_row, mustacheBuildOptions({
        i: data.i,
        undeletable: data.undeletable,
        name: data.name,
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