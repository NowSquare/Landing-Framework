<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/eddystones">{{ trans('eddystones::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ $eddystone['beacon']->description }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">

      <form class="ajax" id="frm" method="post" action="{{ url('eddystones/edit') }}">
        <input type="hidden" name="sl" value="{{ $sl }}">
        {!! csrf_field() !!}

        <div class="panel panel-default">
          <fieldset class="panel-body">
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $eddystone['beacon']->description }}" maxlength="127" required autocomplete="off">
            </div>

            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1"<?php if ($eddystone['beacon']->status == 'ACTIVE') echo ' checked'; ?>>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
          </fieldset>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('eddystones::global.notifications') }}</h3>
          </div>
          <fieldset class="panel-body">

            <table class="table table-list" id="tbl-list">
              <thead>
                <tr>
                  <th style="width:160px">{{ trans('global.language') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.notification_language_help') }}">&#xE887;</i></th>
                  <th>{{ trans('eddystones::global.notification') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.notification_help') }}">&#xE887;</i></th>
                  <th>{{ trans('eddystones::global.link') }}</th>
                  <th style="width:68px"></th>
                  <th style="width:50px"></th>
                </tr>
              </thead>

              <tbody style="border: 1px solid #f3f3f3 !important">
              </tbody>

              <tfoot style="border: 1px solid #f3f3f3 !important">
                <tr>
                  <td colspan="6">
                    <button type="button" class="btn btn-lg btn-block btn-success add_item"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('eddystones::global.add_notification') }}</button>
                  </td>
                </tr>
              </tfoot>
            </table>

          </fieldset>
        </div>

        <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/eddystones" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>

      </form>

    </div>
  </div>

</div>

<script id="list_row" type="x-tmpl-mustache">
<tr data-i="@{{ i }}" id="row@{{ i }}">
  <td>
    <select name="language[]" class="form-control input-lg" style="max-width:150px;">
<?php 
foreach ($languages as $language_code => $language) { 
  echo '<option value="' . $language_code . '" {{#language=' . $language_code . '}}selected{{/language=' . $language_code . '}}>' . $language['name'] . '</option>';
}
?>
    </select>
  </td>
  <td>
    <input type="text" class="form-control input-lg" id="notification@{{ i }}" name="notification[]" maxlength="40" autocomplete="off" value="@{{ notification }}">
  </td>
  <td>
    <select class="form-control input-lg select-url">
      <option value=""></option>
      <optgroup label="{{ trans('global.other') }}">
        <option value="custom_link" @{{#url_custom}}selected@{{/url_custom}}>{{ trans('eddystones::global.custom_link') }}</option>
      </optgroup>
      <optgroup label="{{ trans('landingpages::global.module_name_plural') }}">
<?php 
foreach ($sites as $site) { 
  echo '<option value="' . $site->pages->first()->url() . '" {{#url_encoded=' . base64_encode($site->pages->first()->url()) . '}}selected{{/url_encoded=' . base64_encode($site->pages->first()->url()) . '}}>' . $site->funnel->name . ' - ' . $site->name . '</option>';
}
?>
      </optgroup>
      <optgroup label="{{ trans('forms::global.module_name_plural') }}">
<?php 
foreach ($forms as $form) { 
  echo '<option value="' . $form->url() . '" {{#url_encoded=' . base64_encode($form->url()) . '}}selected{{/url_encoded=' . base64_encode($form->url()) . '}}>' . $form->funnel->name . ' - ' . $form->name . '</option>';
}
?>
      </optgroup>
    </select>
  </td>
  <td>
    <button type="button" class="btn btn-lg btn-info btn-popover @{{^url_custom}}display-none@{{/url_custom}} custom-url-btn" title="{{ trans('global.link') }}" data-toggle="tooltip" style="padding: 10px 16px 11px;"><i class="mi insert_link"></i></button>

    <div class="display-none settings-content custom-url-popover">
      <div class="form-group">
        <textarea class="form-control custom-url" name="url[]" style="width:100%;height:52px;" placeholder="https://">@{{url}}</textarea>
      </div>
      <div class="form-group pull-right">
        <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
        <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
      </div>
    </div>

  </td>
  <td align="right">
    <button type="button" class="btn btn-lg btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" style="padding: 10px 16px 11px;"><i class="mi delete"></i></button>
  </td>
</tr>
</script>
<style type="text/css">
  .display-none {
    display: none;
  }
  .popover {
    width: 360px !important;
    max-width: 360px !important;
  }
</style>
<script>
$(function() {
<?php 
// Create array with existing urls
$urls = [];

foreach ($sites as $site) { 
  $urls[] = $site->pages->first()->url();
}

foreach ($forms as $form) { 
  $urls[] = $form->url();
}

echo 'var urls = [];';

foreach ($urls as $url) { 
  echo 'urls.push("' . $url . '");';
}

?>

  var i = 0;

  // Parse template for speed optimization
  // Initialize before inserting existing rows
  var list_row = $('#list_row').html();
  Mustache.parse(list_row);

  // Add attachments
<?php
$i = 0;
foreach ($attachments as $attachment) {
?>
  var data = {};
  data.i = {{ $i }};
  data.language = "{{ $attachment['language'] }}";
  data.notification = "{{ str_replace('"', '&quot;', $attachment['notification']) }}";
  data.url = "{{ $attachment['url'] }}";
  data.url_encoded = "{{ base64_encode($attachment['url']) }}";
  data.url_custom = ($.inArray(data.url, urls) === -1);

  addRepeaterRow('insert', data);
<?php
    $i++;
  }
?>

  $('.add_item').on('click', function() {
    addRepeaterRow('new', null);
  });

  function addRepeaterRow(action, data) {
    if(action == 'new') {

      var html = Mustache.render(list_row, mustacheBuildOptions({
        i: i++,
        language: '{{ $browser_language }}',
        notification: '',
        url: '',
        url_encoded: '',
        url_custom: false
      }));

      $('#tbl-list tbody').append(html);
      rowBindings();
      bsTooltipsPopovers();

    } else if (action == 'insert') {

      var html = Mustache.render(list_row, mustacheBuildOptions({
        i: data.i,
        language: data.language,
        notification: data.notification,
        url: data.url,
        url_encoded: data.url_encoded,
        url_custom: data.url_custom
      }));

      $('#tbl-list tbody').append(html);
      rowBindings();
      bsTooltipsPopovers();
    }
  }

  $('#tbl-list').on('click', '.btn-delete', function() {
    var row = $(this).parents('tr');
    row.remove();
  });

  $('#tbl-list').on('change', '.select-url', function() {
    var $row = $(this).parents('tr');

    if ($(this).val() == 'custom_link') {
      $row.find('.custom-url-btn').show();
    } else {
      $row.find('.custom-url-btn').hide();
    }
  });

  $('html').on('click', function (e) {
    /*console.log(e.target);*/
    $('.btn-popover').each(function () {
      if (! $(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0 && 
        $('.datepicker').has(e.target).length === 0 && 
        ! $(e.target).hasClass('day') && 
        ! $(e.target).hasClass('popover') && 
        ! $(e.target).is('#cboxClose') && 
        ! $(e.target).is('#cboxOverlay')) {

        if (typeof $(this).popover !== 'undefined')
        {
          if ($(this).next('.popover').is(':visible'))
          {
            $(this).popover('hide');
            $(this).next('.popover').remove();
          }
        }
      }
    });
  });

  $('#tbl-list').on('click', '.close-popover', function (e) {
    var popover = $(this).closest('.popover').prev('.btn-popover');
    $(popover).popover('hide');
    $(popover).next('.popover').remove();
  });

  function rowBindings() {
    $('table#tbl-list > tbody > tr').not('.binded').each(function() {
      var $tr = $(this);
      var data_i = $(this).attr('data-i');
      $tr.addClass('binded');

      // Set url when changing existing url dropdown
      $('#row' + data_i).on('change', '.select-url', function() {
        var url = $(this).val();
        if (url != 'custom_link') {
          $tr.find('.settings-content .custom-url').html(url);
        }
      });

      /* Custom url */
      var url_popover = $(this).find('.custom-url-popover');
      var url_button = $(this).find('.custom-url-btn');

      $(url_button).popover({
        trigger: 'manual',
        placement:'left', 
        html : true, 
        content: function() { 
          return $(url_popover).html();
        }
      })
      .click(function(e) {
        if ($(this).next('.popover').is(':visible')) {
          $(this).popover('hide');
          $(this).next('.popover').remove();
        } else {
          $(this).popover('show'); /* show popover now it's setup */
        }
        e.preventDefault();
      }).on('shown.bs.popover', function (e) {
        var popover = $(this);

        //$(this).data("bs.popover").tip().css({'max-width': '320px', 'width': '100%'});

        /* Bind save */
        $(this).next().find('.popover-content .btn-save').on('click', function() {
          /* Get values(s) from popover */
          var custom_url = $tr.find('.popover-content .custom-url').val();

          /* Set value(s) to hidden form */
          $tr.find('.settings-content .custom-url').html(custom_url);

          /* Close popover */
          $(popover).popover('hide'); $(popover).next('.popover').remove();
        });
      });
    });
  }

});
</script>