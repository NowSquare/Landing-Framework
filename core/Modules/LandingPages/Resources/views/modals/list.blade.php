@extends('landingpages::layouts.modal')

@section('content') 

<script src="{{ url('assets/js/material-icons.js') }}"></script>

<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.list') }}</h1>
  </div>
  <div class="row">
    <div class="col-xs-12">

      <table class="table table-list" id="tbl-list">
        <thead>
          <tr>
            <th style="width:49px"></th>
            <th style="width:60px" class="text-center">{{ trans('landingpages::global.icon') }}</th>
            <th>{{ trans('landingpages::global.title') }}</th>
            <th>{{ trans('landingpages::global.link') }}</th>
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

<script id="list_row" type="x-tmpl-mustache">
<tr data-i="@{{ i }}" id="row@{{ i }}">
  <td>
    <div class="order-handle">
      <i class="material-icons">&#xE5D2;</i>
    </div>
  </td>
  <td>
    <button type="button" class="btn btn-block btn-lg btn-default icon-picker iconpicker-component" data-toggle="dropdown" data-selected="@{{ icon }}"><i class="fa @{{ icon }}"></i></button>
  </td>
  <td>
    <input type="text" class="form-control input-lg" id="title@{{ i }}" name="title" autocomplete="off" value="@{{ title }}">
  </td>
  <td>
    <input type="text" class="form-control input-lg" id="url@{{ i }}" name="url" autocomplete="off" value="@{{ url }}" placeholder="http://">
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

  // Parse template for speed optimization
  // Initialize before inserting existing rows
  var list_row = $('#list_row').html();
  Mustache.parse(list_row);

<?php if ($repeat == 'a') { ?>

  $el.find('a').each(function (i) {
    var $row = $(this);
    var data = {};

    var icon = $row.find('i').attr('class');
    icon = icon.replace('fa ', '');
    var title = $row.attr('title');
    title = (typeof title !== typeof undefined && title !== false) ? title : '';
    var url = $row.attr('href');
    url = (typeof url !== typeof undefined && url !== false) ? url : '';

    data.i = i;
    data.icon = icon;
    data.title = title;
    data.url = url;

    addRepeaterRow('insert', data);
  });

<?php } ?>

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>


<?php if ($repeat == 'a') { ?>

  // Get one element for cloning, remove class
  var $item = $el.find('a').first()

  var icon = $item.find('i').attr('class');
  icon = icon.replace('fa ', '');
  $item.find('i').removeClass(icon);
  $item = $item.clone();

  // Make empty before inserting new
  $el.html('');

  $('#tbl-list tbody tr').each(function (i) {
    var $row = $(this);

    var icon = $row.find('.icon-picker i').attr('class');
    icon = icon.replace('fa ', '').replace('iconpicker-component', '');
    var title = $row.find('[name=title]').val();
    var url = $row.find('[name=url]').val();

    var $new_item = $item.clone();

    $new_item.find('i').addClass(icon);
    $new_item.attr('title', title);
    $new_item.attr('href', url);

    $el.append($new_item);
  });

<?php } ?>

  // Changes detected
  window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });

<?php /* ----------------------------------------------------------------------------
List template
*/ ?>

  $('#tbl-list tbody').sortable({
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

  var i = 0;

  $('.add_item').on('click', function() {
    addRepeaterRow('new', null);
  });

  function addRepeaterRow(action, data) {
    if(action == 'update') {
      var html = Mustache.render(list_row, mustacheBuildOptions({
        icon: data.icon,
        title: data.title,
        url: data.url
      }));

      $('tbl-list #row' + data.i).replaceWith(html);

    } else if(action == 'new') {

      var html = Mustache.render(list_row, mustacheBuildOptions({
        i: i++,
        icon: 'fa-envelope-o',
        title: '',
        url: '#'
      }));

      $('#tbl-list tbody').append(html);
      rowBindings();

    } else if (action == 'insert'){

      var html = Mustache.render(list_row, mustacheBuildOptions({
        i: i++,
        icon: data.icon,
        title: data.title,
        url: data.url
      }));

      $('#tbl-list tbody').append(html);
      rowBindings();
    }
  }

  $('#tbl-list').on('click', '.btn-delete', function() {
    $(this).parents('tr').remove();
  });
});

function rowBindings() {
  $('.icon-picker').iconpicker({
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
        } else if(val.match(/^iml-/)){
          return 'iml ' + val;
        } else {
          return 'mi ' + val;
        }
      }
  });

  bsTooltipsPopovers();
}
</script>
@endsection