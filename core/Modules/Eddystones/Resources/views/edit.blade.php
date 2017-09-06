<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/eddystones">{{ trans('eddystones::global.eddystone_beacons') }}</a>
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

            <table class="table table-list table-condensed" id="tbl-list">
              <thead>
                <tr>
                  <th style="width:160px">{{ trans('global.language') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.notification_language_help') }}">&#xE887;</i></th>
                  <th>{{ trans('eddystones::global.notification') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.notification_help') }}">&#xE887;</i></th>
                  <th style="width:302px;">{{ trans('eddystones::global.when') }}</th>
                  <th style="width:165px"></th>
                  <th>{{ trans('eddystones::global.link') }}</th>
                  <th style="width:50px"></th>
                  <th style="width:50px"></th>
                </tr>
              </thead>

              <tbody style="border: 1px solid #f3f3f3 !important">
              </tbody>

              <tfoot style="border: 1px solid #f3f3f3 !important">
                <tr>
                  <td colspan="7">
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
    <select name="language[]" class="form-control" style="max-width:150px;">
<?php 
foreach ($languages as $language_code => $language) { 
  echo '<option value="' . $language_code . '" {{#language=' . $language_code . '}}selected{{/language=' . $language_code . '}}>' . $language['name'] . '</option>';
}
?>
    </select>
  </td>
  <td>
    <input type="text" class="form-control" id="notification@{{ i }}" name="notification[]" maxlength="40" autocomplete="off" value="@{{{ notification }}}">
  </td>
  <td>
     <select multiple="multiple" class="days_of_week" data-placeholder="{{ trans('eddystones::global.every_day') }}" name="days_of_week[@{{ i }}][]">
<?php 
foreach (trans('eddystones::global.days_of_week_short') as $i => $day) { 
  echo '<option value="' . ($i + 1) . '" {{#days_of_week_' . ($i + 1) . '}}selected{{/days_of_week_' . ($i + 1) . '}}>' . $day . '</option>';
}
?>
     </select>
  </td>
  <td>
      <div class="time">
        <div class="form-group input-group timepicker-holder" style="width:155px; margin-bottom: 0">
          <input type="text" class="form-control text-center timepicker-component startTimeOfDay" value="@{{startTimeOfDay}}" placeholder="0:00" name="startTimeOfDay[]" maxlength="5">
          <span class="input-group-addon text-lowercase">-</span>
          <input type="text" class="form-control text-center timepicker-component endTimeOfDay" value="@{{endTimeOfDay}}" placeholder="23:59" name="endTimeOfDay[]" maxlength="5">
        </div>
      </div>
  </td>
  <td>
    <select class="form-control select-url">
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
    <button type="button" class="btn btn-info btn-popover @{{^url_custom}}display-none@{{/url_custom}} custom-url-btn" title="{{ trans('global.link') }}" data-toggle="tooltip" style="padding: 4px 10px;"><i class="mi insert_link"></i></button>

    <div class="display-none settings-content custom-url-popover">
      <div class="form-group">
        <textarea class="form-control custom-url" name="url[]" style="width:100%;height:52px;" placeholder="https://">@{{url}}</textarea>
        <small class="help-block">{!! trans('eddystones::global.custom_link_help') !!}</small>
      </div>
      <div class="form-group pull-right">
        <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
        <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
      </div>
    </div>

  </td>
  <td align="right">
    <button type="button" class="btn btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" style="padding: 4px 10px;"><i class="mi delete"></i></button>
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
  // Parse template for speed optimization
  // Initialize before inserting existing rows
  var list_row = $('#list_row').html();
  Mustache.parse(list_row);

  // Add attachments
<?php
$i = 0;
foreach ($attachments as $attachment) {
  $days_of_week = (isset($attachment['targeting']['anyOfDaysOfWeek'])) ? $attachment['targeting']['anyOfDaysOfWeek'] : [];
  if (count($days_of_week) == 7) $days_of_week = [];

  $days_of_week_1 = (in_array(1, $days_of_week)) ? 'true' : 'false';
  $days_of_week_2 = (in_array(2, $days_of_week)) ? 'true' : 'false';
  $days_of_week_3 = (in_array(3, $days_of_week)) ? 'true' : 'false';
  $days_of_week_4 = (in_array(4, $days_of_week)) ? 'true' : 'false';
  $days_of_week_5 = (in_array(5, $days_of_week)) ? 'true' : 'false';
  $days_of_week_6 = (in_array(6, $days_of_week)) ? 'true' : 'false';
  $days_of_week_7 = (in_array(7, $days_of_week)) ? 'true' : 'false';

  $startTimeOfDay = (isset($attachment['targeting']['startTimeOfDay'])) ? $attachment['targeting']['startTimeOfDay'] : '0:00';
  $endTimeOfDay = (isset($attachment['targeting']['startTimeOfDay'])) ? $attachment['targeting']['endTimeOfDay'] : '23:59';
?>
  var data = {};
  data.i = {{ $i }};
  data.language = "{{ $attachment['language'] }}";
  data.notification = "{{ str_replace('"', '&quot;', $attachment['notification']) }}";
  data.url = "{{ $attachment['url'] }}";
  data.url_encoded = "{{ base64_encode($attachment['url']) }}";
  data.url_custom = ($.inArray(data.url, urls) === -1);
  data.days_of_week = [{{ implode("], [", $days_of_week) }}];
  data.days_of_week_1 = {{ $days_of_week_1 }};
  data.days_of_week_2 = {{ $days_of_week_2 }};
  data.days_of_week_3 = {{ $days_of_week_3 }};
  data.days_of_week_4 = {{ $days_of_week_4 }};
  data.days_of_week_5 = {{ $days_of_week_5 }};
  data.days_of_week_6 = {{ $days_of_week_6 }};
  data.days_of_week_7 = {{ $days_of_week_7 }};
  data.startTimeOfDay = '{{ $startTimeOfDay }}';
  data.endTimeOfDay = '{{ $endTimeOfDay }}';

  addRepeaterRow('insert', data);
<?php
    $i++;
  }
?>
  var i = {{ $i }};

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
        url_custom: false,
        days_of_week_1: false,
        days_of_week_2: false,
        days_of_week_3: false,
        days_of_week_4: false,
        days_of_week_5: false,
        days_of_week_6: false,
        days_of_week_7: false,
        startTimeOfDay: '0:00',
        endTimeOfDay: '23:59'
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
        url_custom: data.url_custom,
        days_of_week_1: data.days_of_week_1,
        days_of_week_2: data.days_of_week_2,
        days_of_week_3: data.days_of_week_3,
        days_of_week_4: data.days_of_week_4,
        days_of_week_5: data.days_of_week_5,
        days_of_week_6: data.days_of_week_6,
        days_of_week_7: data.days_of_week_7,
        startTimeOfDay: data.startTimeOfDay,
        endTimeOfDay: data.endTimeOfDay
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

<?php
/*
 * -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Days
 */
?>

      $tr.find('.days_of_week').select2();

<?php
/*
 * -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Time
 */
?>
      /* Set dates */
      var startTimeOfDay = $tr.find('.startTimeOfDay').val();
      var endTimeOfDay = $tr.find('.endTimeOfDay').val();
      if (startTimeOfDay == '') startTimeOfDay = '0:00';
      if (endTimeOfDay == '') endTimeOfDay = '23:59';

      /* Time picker */
      var timepicker_opts = {
        minuteStep: 1,
        appendWidgetTo: 'body',
        showSeconds: false,
        showMeridian: false,
        showInputs: false,
        maxHours: 24,
        orientation: $('body').hasClass('right-to-left') ? { x: 'right', y: 'auto'} : { x: 'auto', y: 'auto'}
      };

      timepicker_opts.defaultTime = startTimeOfDay;

      $tr.find('.startTimeOfDay').timepicker(timepicker_opts);

      timepicker_opts.defaultTime = endTimeOfDay;

      $tr.find('.endTimeOfDay').timepicker(timepicker_opts);

    });
  }

});
</script>