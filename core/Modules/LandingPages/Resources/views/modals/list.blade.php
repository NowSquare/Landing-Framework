@extends('landingpages::layouts.modal')

@section('content') 

<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.list') }}</h1>
  </div>
  <div class="row">
    <div class="col-xs-12">

      <table class="table" id="tbl-list">
        <thead>
          <tr>
            <th>Order</th>
            <th>Icon</th>
            <th>Title</th>
            <th>Link</th>
          </tr>
        </thead>

        <tbody style="border: 1px solid #f3f3f3 !important">
        </tbody>

        <tfoot style="border: 1px solid #f3f3f3 !important">
          <tr colspan="4">
            <button type="button" class="btn btn-block btn-success add_item">Add</button>
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
<tr data-i="@{{ i }}">
  <td>
    -
  </td>
  <td>
    Icon
  </td>
  <td>
    <input type="text" class="form-control" id="title@{{ i }}" name="title[]" autocomplete="off" value="">
  </td>
  <td>
    <input type="text" class="form-control" id="url@{{ i }}" name="url[]" autocomplete="off" value="">
  </td>
  <td align="right">
    <button type="button" class="btn btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-times"></i></button>
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

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
<?php if ($el_class != '') { ?>

  // Changes detected
  window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });

<?php /* ----------------------------------------------------------------------------
List template
*/ ?>
  var i = 0;
  var list_row = $('#list_row').html();

  Mustache.parse(list_row); // optional, speeds up future uses

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
        icon: null,
        title: '',
        url: ''
      }));

      $('#tbl-list tbody').append(html);

    } else if (action == 'insert'){

      var html = Mustache.render(list_row, mustacheBuildOptions({
        i: i++,
        icon: data.icon,
        title: data.title,
        url: data.url
      }));

      $('#tbl-list tbody').append(html);
    }
  }
});
</script>
@endsection