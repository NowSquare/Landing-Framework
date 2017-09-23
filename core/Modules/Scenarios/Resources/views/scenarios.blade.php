<div class="container">
 
  <div class="row m-t">
    <div class="col-sm-12">
     
       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('scenarios::global.module_name_plural') }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
              <a href="#/scenarios/analytics" class="btn btn-primary"><i class="fa fa-pie-chart" aria-hidden="true"></i> {{ trans('scenarios::global.analytics') }}</a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li>
                <a href="javascript:void(0);" class="btn-qr">{{ trans('scenarios::global.api') }}</a>
              </li>
            </ul>

          </div>
        </div>
      </nav>
    
    </div>
  </div>
<script>
$('.btn-qr').on('click', function() {

  $.colorbox({
    href: app_root + '/scenarios/qr',
    fastIframe: true,
    overlayClose: false,
    iframe: true,
    width: 580,
    height: 580, 
    transition: 'none', 
    fadeOut: 0,
    onOpen: function() {
      $('#colorbox').attr('id', 'colorbox2');
    }, 
    onLoad: function() {
      $('html, body').css('overflow', 'hidden'); // page scrollbars off
    }, 
    onClosed:function() {
      $('html, body').css('overflow', ''); // page scrollbars on
    }
  });

});
</script>

<style type="text/css">
.popover-content .img-browse {
  width: 200px;
}
#tbl-scenarios td {
  padding:5px !important;
}
.select2-primary .form-group {
  margin-bottom:0;
}
.select2-primary label {
  padding-top:6px;
}
.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
  vertical-align:middle;
}
.btn-fixed-width {
  width: 44px;
}
.date-start-end .input-group-addon,
.popover-content .input-group-addon {
  padding:8px 0;
  float: left;
  width: 29px;
  height: 30px;

}
.time .input-group-addon {
  padding: 10px 0;
  float: left;
  width: 29px;
  height: 34px;
}
.input-daterange .form-control {
  width:106px;
}
.timepicker-holder .form-control {
  width:60px;
}
.date-start-end, 
.date-single, 
.btn-settings,
.btn-app,
.btn-site,
.settings-content {
  display:none;
}
.timepicker-component {
  text-align:center;
}
  
.table th {
  border-bottom-width: 1px !important;
}
</style>

  <div class="scenario_warning_message alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {!! trans('scenarios::global.scenario_warning_message') !!}
  </div>

<script>
if( getCookie('scenario_warning_message') === 'closed' ){
  $('.scenario_warning_message').hide();
}

$('.close').on('click', function(e) {
  e.preventDefault();
  setCookie('scenario_warning_message', 'closed', 30);
});
</script>

  <div class="card-box">
    <table class="table table-hover" id="tbl-scenarios">
      <thead>
        <tr>
        <th style="width:160px">{{ trans('scenarios::global.if_someone') }}</th>
        <th style="width:53px">&nbsp;</th>
        <th style="width:47px">&nbsp;</th>
        <th style="min-width:120px">{{ trans('scenarios::global.where') }}</th>
        <th style="width:160px">{{ trans('scenarios::global.then') }}</th>
        <th style="width:50px">&nbsp;</th>
        <th style="width:302px">{{ trans('scenarios::global.when') }}</th>
        <th style="width:165px">&nbsp;</th>
        <th style="width:53px">&nbsp;</th>
        <th style="width:47px">&nbsp;</th>
        </tr>
      </thead>
      <tbody style="border: 1px solid #f3f3f3 !important">
      </tbody>
    </table>

<script id="scenario_row" type="x-tmpl-mustache">
<tr data-i="@{{ i }}" data-sl="@{{ sl }}">
  <td>
<?php
echo '<select class="scenario-if">';
foreach ($scenario_if as $statement) 
{
  if ($statement->id < 3) {
    //if ($statement->id == 1) echo '<optgroup label="' . trans('scenarios::global.available_app_close') . '">';
    //if ($statement->id == 3) echo '<optgroup label="' . trans('scenarios::global.available_app_open') . '">';
    echo '<option value="' . $statement->id . '" {{#scenario_if=' . $statement->id . '}}selected{{/scenario_if=' . $statement->id . '}}>' . trans('scenarios::global.' . $statement->name) . '</option>';
  }

  //if ($statement->id == 2 || $statement->id == 5) echo '</optgroup>';
}
echo '</select>';
?>
    </td>
    <td>
      <button class="btn btn-primary btn-notification btn-popover" data-toggle="tooltip" title="{{ trans('scenarios::global.notification') }}"><i class="fa fa-bell" aria-hidden="true"></i></button>

      <div class="settings-content notification-content">

        <span class="help-block">{!! trans('scenarios::global.notification_help') !!}</span>

        <div class="form-group">
          <input type="text" class="form-control notification_title" value="@{{notification_title}}" placeholder="{{ trans('scenarios::global.push_notification_title') }}">
        </div>
        <div class="form-group">
          <textarea class="form-control notification" style="width:350px;height:68px;" placeholder="{{ trans('scenarios::global.push_notification_text') }}">@{{notification}}</textarea>
        </div>
        <div class="form-group pull-right">
          <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
          <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
        </div>
      </div>

    </td>

    <td>

      <button class="btn btn-primary btn-popover btn-app_image" data-toggle="tooltip" title="{{ trans('scenarios::global.app_image') }}"><i class="fa fa-picture-o"></i></button>
      <div class="settings-content app_image-content">

        <span class="help-block">{!! trans('scenarios::global.app_image_help') !!}</span>

        <div class="form-group">
          <input type="hidden" class="app_image" id="app_image@{{i}}" value="@{{app_image}}">

          <div id="app_image@{{i}}-image" class="show-image-container">
            @{{#app_image_thumb}}
            <img src="@{{ app_image_thumb }}" class="thumbnail" style="max-width:100%;margin:10px 0 0 0;">
            @{{/app_image_thumb}}
          </div>

        </div>

        <div class="form-group">

            <div class="btn-group" role="group">
              <button type="button" class="btn btn-primary img-browse" data-id="app_image@{{i}}"><i class="fa fa-picture-o"></i> {{ trans('scenarios::global.select_image') }}</button>
              <button type="button" class="btn btn-danger img-remove" data-id="app_image@{{i}}" title="{{ trans('scenarios::global.remove_image') }}"><i class="fa fa-ban"></i></button>
            </div>

        </div>
          
        <div class="form-group pull-right">
          <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
          <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
        </div>
      </div>

    </td>
    <td>
     <select multiple="multiple" class="scenario-places" data-placeholder="">
<?php
// Beacons
foreach($beacons as $beacon) {
  echo '<option value="beacon' . $beacon->id . '" {{#beacons}} {{#' . $beacon->id . '}} selected="selected" {{/' . $beacon->id . '}} {{/beacons}} data-type="beacon">' . $beacon->name . '</option>';
}

// Geofences
foreach($geofences as $geofence) {
  echo '<option value="geofence' . $geofence->id . '" {{#geofences}} {{#' . $geofence->id . '}} selected="selected" {{/' . $geofence->id . '}} {{/geofences}} data-type="geofence">' . $geofence->name . '</option>';
}
?>
          </select>


    </td>
    <td>
<?php
echo '<select class="scenario-then">';

echo '<option value="">' . trans('scenarios::global.do_nothing') . '</option>';
foreach ($scenario_then as $statement) 
{
  echo '<option value="' . $statement->id . '" {{#scenario_then=' . $statement->id . '}}selected{{/scenario_then=' . $statement->id . '}}>' . trans('scenarios::global.' . $statement->name) . '</option>';
}
echo '</select>';
?>
    </td>
    <td>

        <button class="btn btn-primary btn-settings btn-popover btn-img btn-fixed-width" data-toggle="tooltip" title="{{ trans('scenarios::global.image') }}"><i class="fa fa-picture-o"></i></button>

        <div class="settings-content img-content">
        
          <div class="form-group">
            <input type="hidden" class="show-img" id="show_image@{{i}}" value="@{{show_image}}">

            <div id="show_image@{{i}}-image" class="show-image-container">
              @{{#show_image_thumb}}
              <img src="@{{ show_image_thumb }}" class="thumbnail" style="max-width:100%;margin:10px 0 0 0;">
              @{{/show_image_thumb}}
            </div>

          </div>

          <div class="form-group">

            <div class="btn-group" role="group">
              <button type="button" class="btn btn-primary img-browse" data-id="show_image@{{i}}"><i class="fa fa-picture-o"></i> {{ trans('scenarios::global.select_image') }}</button>
              <button type="button" class="btn btn-danger img-remove" data-id="show_image@{{i}}" title="{{ trans('scenarios::global.remove_image') }}"><i class="fa fa-ban"></i></button>
            </div>

          </div>

          <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
            <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
          </div>
        </div>

        <button class="btn btn-primary btn-settings btn-tpl btn-fixed-width" data-toggle="tooltip" title="{{ trans('scenarios::global.template') }}"><i class="fa fa-file-text-o"></i></button>

        <div class="settings-content tpl-content">
          <div class="show-template">@{{template}}</div>
<?php /*
          <div class="form-group">
            <label>{{ trans('scenarios::global.template') }}</label>
            <textarea class="form-control show-template">@{{template}}</textarea>
          </div>
          <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
            <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
          </div>
*/ ?>
        </div>

        <button class="btn btn-primary btn-settings btn-popover btn-url btn-fixed-width" data-toggle="tooltip" title="{{ trans('scenarios::global.url') }}"><i class="fa fa-link"></i></button>

        <div class="settings-content url-content">
          <div class="form-group">
            <textarea class="form-control open-url" style="width:100%;height:52px;" placeholder="http://">@{{open_url}}</textarea>
          </div>
          <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
            <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
          </div>
        </div>

  
  </td>
    <td>

     <select multiple="multiple" class="days_of_week" data-placeholder="{{ trans('scenarios::global.every_day') }}" name="days_of_week[@{{ i }}][]">
<?php 
foreach (trans('scenarios::global.days_of_week_short') as $key => $day) { 
  echo '<option value="' . $key . '" {{#day_of_week_' . $key . '}}selected{{/day_of_week_' . $key . '}}>' . $day . '</option>';
}
?>
     </select>

    </td>
    <td>

      <div class="time">
        <div class="form-group input-group timepicker-holder" style="width:155px; margin-bottom: 0">
          <input type="text" class="form-control timepicker-component scenario-time-start" value="@{{time_start}}" placeholder="00:00">
          <span class="input-group-addon text-lowercase">-</span>
          <input type="text" class="form-control timepicker-component scenario-time-end" value="@{{time_end}}" placeholder="23:59">
        </div>
      </div>

    </td>
    <td>
        <button class="btn btn-primary btn-popover btn-date" data-toggle="tooltip" title="{{ trans('scenarios::global.date_range') }}"><i class="fa fa-calendar-o"></i></button>
        <div class="date-start-end">
          <div class="form-group input-daterange input-group datepicker-component" style="width:232px">
            <input type="text" class="input-sm form-control scenario-date-start" value="@{{date_start}}" placeholder="{{ trans('scenarios::global.start_date') }}">
            <span class="input-group-addon text-lowercase">-</span>
            <input type="text" class="input-sm form-control scenario-date-end" value="@{{date_end}}" placeholder="{{ trans('scenarios::global.end_date') }}">
          </div>
          <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-sm btn-save"><i class="fa fa-check"></i></button>
            <button type="button" class="btn btn-default btn-sm close-popover"><i class="fa fa-times"></i></button>
          </div>
        </div>

    </td>
    <td align="right">
      <button type="button" class="btn btn-danger btn-delete" title="{{ trans('global.delete') }}" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></button>
    </td>
  </tr>
</script>

    <button type="button" class="btn btn-lg btn-success m-t-10 btn-block add_scenario"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; {{ trans('scenarios::global.add_scenario') }}</button>
  </div>

</div>

<script id="default_template" type="x-tmpl-mustache">
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="m-2">
        <h1>Headline</h1>
        <p class="lead">Update this text to suit your needs.</p>
        <div class="alert alert-info" role="alert">
        You can use <a href="https://getbootstrap.com//" target="_blank">Bootstrap 4</a> markup!
        </div>
      </div>
    </div>
  </div>
</div>
</script>

<script>
var i = 0;
var scenario_row = $('#scenario_row').html();
var default_template = $('#default_template').html();
var thumb_width = 232;
var thumb_height = 0;
var thumb_type = 'resize';

Mustache.parse(scenario_row); // optional, speeds up future uses

<?php
// Add existing rows
$i = 0;
$js = '';

foreach($scenarios as $scenario)
{
  // Attached geofences to id array
  $scenario_geofences = $scenario->geofences()->get();
  //$geofence_array = array();
  $geofence_obj = new StdClass;
  foreach($scenario_geofences as $geofence)
  {
    $geofence_obj->{$geofence->geofence_id} = true;
    //array_push($geofence_array, $geofence->id);
  }
  //$scenario->geofences = $geofence_array;
  $scenario->geofences = $geofence_obj;

  // Attached beacons to id array
  $scenario_beacons = $scenario->beacons()->get();

  //$beacon_array = array();
  $beacon_obj = new StdClass;
  foreach($scenario_beacons as $beacon)
  {
    $beacon_obj->{$beacon->beacon_id} = true;
   // array_push($beacon_array, $beacon->id);
  }
  //$scenario->beacons = $beacon_array;}
  $scenario->beacons = $beacon_obj;

  $day_of_week_mo = ((bool) $scenario->day_of_week_mo) ? 1 : 0;
  $day_of_week_tu = ((bool) $scenario->day_of_week_tu) ? 1 : 0;
  $day_of_week_we = ((bool) $scenario->day_of_week_we) ? 1 : 0;
  $day_of_week_th = ((bool) $scenario->day_of_week_th) ? 1 : 0;
  $day_of_week_fr = ((bool) $scenario->day_of_week_fr) ? 1 : 0;
  $day_of_week_sa = ((bool) $scenario->day_of_week_sa) ? 1 : 0;
  $day_of_week_su = ((bool) $scenario->day_of_week_su) ? 1 : 0;

  if ($day_of_week_mo && $day_of_week_tu && $day_of_week_we && $day_of_week_th && $day_of_week_fr && $day_of_week_sa && $day_of_week_su) {
    $day_of_week_mo = 0;
    $day_of_week_tu = 0;
    $day_of_week_we = 0;
    $day_of_week_th = 0;
    $day_of_week_fr = 0;
    $day_of_week_sa = 0;
    $day_of_week_su = 0;
  }

  $sl_scenario = \Platform\Controllers\Core\Secure::array2string(array('scenario_id' => $scenario->id));

  $json_string = str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($scenario)));
  //$json_string = json_encode($scenario);
  //dd($json_string);
  $js .= "var data = JSON.parse('" . $json_string . "');
data.sl = '" . $sl_scenario . "';
data.day_of_week_mo = " . $day_of_week_mo . ";
data.day_of_week_tu = " . $day_of_week_tu . ";
data.day_of_week_we = " . $day_of_week_we . ";
data.day_of_week_th = " . $day_of_week_th . ";
data.day_of_week_fr = " . $day_of_week_fr . ";
data.day_of_week_sa = " . $day_of_week_sa . ";
data.day_of_week_su = " . $day_of_week_su . ";
data.i = '" . $i . "';";

  $js .= "addRepeaterRow('insert', data);";
  $i++;
}

echo "setTimeout(function() {";
echo $js;
echo "}, 100);";
?>

$('.add_scenario').on('click', function() {
  addRepeaterRow('new', null);
});

function addRepeaterRow(action, data)
{
  if(action == 'update') {
    var app_image_thumb = (typeof data.app_image !== 'undefined' && data.app_image != '' && data.app_image != null) ? app_root + '/platform/thumbnail?w=' + thumb_width + '&h=' + thumb_height + '&t=' + thumb_type + '&img=' + data.app_image : '';

    var show_image_thumb = (typeof data.show_image !== 'undefined' && data.show_image != '' && data.show_image != null) ? app_root + '/platform/thumbnail?w=' + thumb_width + '&h=' + thumb_height + '&t=' + thumb_type + '&img=' + data.show_image : '';

    var html = Mustache.render(scenario_row, mustacheBuildOptions({
      sl: data.sl,
      geofences: data.geofences,
      beacons: data.beacons,
      scenario_if: data.scenario_if_id,
      scenario_then: data.scenario_then_id,
      day_of_week_mo: data.day_of_week_mo,
      day_of_week_tu: data.day_of_week_tu,
      day_of_week_we: data.day_of_week_we,
      day_of_week_th: data.day_of_week_th,
      day_of_week_fr: data.day_of_week_fr,
      day_of_week_sa: data.day_of_week_sa,
      day_of_week_su: data.day_of_week_su,
      date_start: data.date_start,
      date_end: data.date_end,
      scenario_time: data.scenario_time_id,
      time_start: data.time_start,
      time_end: data.time_end,
      notification_title: data.notification_title,
      notification: data.notification,
      app_image: data.app_image,
      app_image_thumb: app_image_thumb,
      frequency: data.frequency,
      delay: data.delay,
      show_image: data.show_image,
      show_image_thumb: show_image_thumb,
      open_url: data.open_url,
      template: data.template
    }));

    $('tbl-scenarios #row' + data.i).replaceWith(html);

  } else if(action == 'new') {
    var request = $.ajax({
      url: "{{ url('scenarios/scenario?token=' . $jwt_token) }}",
      type: 'POST',
      data: {funnel_id : {{ $funnel->id }} },
      dataType: 'json'
    });

    request.done(function(json) {
      if (json.result == 'error') {
        swal({
          title: json.result_msg,
          type: "error",
          showCancelButton: false,
          confirmButtonText: _lang['ok']
        });
      } else {
        var html = Mustache.render(scenario_row, mustacheBuildOptions({
          i: i++,
          sl: json.sl,
          geofences: {},
          beacons: {},
          scenario_if: 1,
          scenario_then: 1,
          day_of_week_mo: 0,
          day_of_week_tu: 0,
          day_of_week_we: 0,
          day_of_week_th: 0,
          day_of_week_fr: 0,
          day_of_week_sa: 0,
          day_of_week_su: 0,
          date_start: null,
          date_end: null,
          scenario_time: 1,
          time_start: null,
          time_end: null,
          notification_title: '',
          notification: '',
          app_image: '',
          app_image_thumb: '',
          frequency: 0,
          delay: 0,
          show_image: '',
          show_image_thumb: '',
          open_url: '',
          template: default_template,
        }));

        $('#tbl-scenarios tbody').append(html);
        rowBindings();
        bsTooltipsPopovers();
        showSaved();
      }
    });

    request.fail(function(jqXHR, textStatus) {
      alert('Request failed, please try again (' + textStatus + ')');
    });
  } else if (action == 'insert') {
    var app_image_thumb = (typeof data.app_image !== 'undefined' && data.app_image != '' && data.app_image != null) ? app_root + '/platform/thumbnail?w=' + thumb_width + '&h=' + thumb_height + '&t=' + thumb_type + '&img=' + data.app_image : '';

    var show_image_thumb = (typeof data.show_image !== 'undefined' && data.show_image != '' && data.show_image != null) ? app_root + '/platform/thumbnail?w=' + thumb_width + '&h=' + thumb_height + '&t=' + thumb_type + '&img=' + data.show_image : '';

    var template = (data.template == null) ? default_template : data.template;

    var html = Mustache.render(scenario_row, mustacheBuildOptions({
      i: i++,
      sl: data.sl,
      scenario_if: data.scenario_if_id,
      geofences: data.geofences,
      beacons: data.beacons,
      scenario_then: data.scenario_then_id,
      day_of_week_mo: data.day_of_week_mo,
      day_of_week_tu: data.day_of_week_tu,
      day_of_week_we: data.day_of_week_we,
      day_of_week_th: data.day_of_week_th,
      day_of_week_fr: data.day_of_week_fr,
      day_of_week_sa: data.day_of_week_sa,
      day_of_week_su: data.day_of_week_su,
      date_start: data.date_start,
      date_end: data.date_end,
      scenario_time: data.scenario_time_id,
      time_start: data.time_start,
      time_end: data.time_end,
      notification_title: data.notification_title,
      notification: data.notification_message,
      app_image: data.app_image,
      app_image_thumb: app_image_thumb,
      frequency: data.frequency,
      delay: data.delay,
      show_image: data.show_image,
      show_image_thumb: show_image_thumb,
      open_url: data.open_url,
      template: template
    }));

    $('#tbl-scenarios tbody').append(html);
    rowBindings();
    bsTooltipsPopovers();
  }
}

/* Close all other popovers */
/*
$('.btn-popover').on('click', function() {
  var this_btn = $(this);
  $('.btn-popover').each(function () {
    if ($(this).next('div.popover:visible').length) {
      $(this).not(this_btn).popover('hide');
      $(this).not(this_btn).next('.popover').remove();
    }
  });
});
*/

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
/*
$('html').on('click', function (e) {
  /*console.log(e.target);* /
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
*/
$('#tbl-scenarios').on('click', '.close-popover', function (e) {
  var popover = $(this).closest('.popover').prev('.btn-popover');
  $(popover).popover('hide');
  $(popover).next('.popover').remove();
});

var row = 1;

function rowBindings() {
  $('table#tbl-scenarios > tbody > tr').not('.binded').each(function() {
    var $tr = $(this);
    var sl_scenario = $(this).attr('data-sl');
    $tr.addClass('binded');

    /* Check options */
    checkScenarioIf($tr);
    checkScenarioThen($tr);
    checkScenarioTime($tr);
<?php
/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
?>
    /* Scenario if */
    $(this).find('.scenario-if').select2(
    {
      allowClear: false,
      minimumResultsForSearch: -1
    })
    .on("change", function(e) {
      var value = $(this).val();

      checkScenarioIf($tr);

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
        type: 'POST',
        data: {
          sl: sl_scenario,
          name: 'scenario-if',
          value: value
        },
        dataType: 'json'
      });

      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')');});
    });
<?php
/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
?>
    /* Geofences & beacons */
    $(this).find('.scenario-places').select2({
      templateResult: function(result) {
        if (!result.id) return result.text;

        var type = result.element.getAttribute('data-type');

        if (type == 'geofence') return $('<span><i class="fa fa-map-marker" aria-hidden="true"></i> ' + result.text + '</span>');
        if (type == 'beacon') return $('<span><i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' + result.text + '</span>');
      },
      templateSelection: function(result) {
        if (!result.id) return result.text;

        var type = result.element.getAttribute('data-type');

        if (type == 'geofence') return $('<span><i class="fa fa-map-marker" aria-hidden="true"></i> ' + result.text + '</span>');
        if (type == 'beacon') return $('<span><i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' + result.text + '</span>');
      },
      "language": {
        "noResults": function(){
          return "{!! str_replace('"', '\"', trans('scenarios::global.no_beacons_geofences_found')) !!}";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      }
    })
    .on("change", function(e) {
      closeAllPopovers();
      var value = $(this).val();

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario-places?token=' . $jwt_token) }}",
        type: 'POST',
        data: {
          sl: sl_scenario,
          places: value,
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json'
      });

      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
    });

    /* Scenario then */
    $(this).find('.scenario-then').select2(
    {
      allowClear: false,
      minimumResultsForSearch: -1
    })
    .on("change", function(e) {
      var value = $(this).val();

      checkScenarioThen($tr);

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
        type: 'POST',
        data: {
          sl: sl_scenario,
          name: 'scenario-then',
          value: value
        },
        dataType: 'json'
      });
  
      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
    });

<?php
/*
 * -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Days
 */
?>

      $(this).find('.days_of_week').select2()
      .on("change", $.debounce(1600, function(e) {
        closeAllPopovers();
        var value = $(this).val();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {
            sl: sl_scenario,
            name: 'days-of-week',
            value: value,
            _token: '{{ csrf_token() }}'
          },
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      }));

<?php
/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
?>
    /* Scenario when day */
    $(this).find('.scenario-date').select2(
    {
      allowClear: false,
      minimumResultsForSearch: -1
    })
    .on("change", function(e) {
      var value = $(this).val();

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
        type: 'POST',
        data: {
          sl: sl_scenario,
          name: 'scenario-when-date',
          value: value
        },
        dataType: 'json'
      });
  
      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
    });

    /* Date range picker popover */
    var date_range_picker = $(this).find('.date-start-end');
    var btn_date = $(this).find('.btn-date');

    $(btn_date).popover({
      trigger: 'manual',
      placement:'top', 
      html : true, 
      content: function() { 
        /*if (this.cache) return this.cache;
        return this.cache = $(date_range_picker).html();*/
        return $(date_range_picker).html();
      },
      showCallback: function() {

      }
    }).click(function(e) {
      if ($(this).next('.popover').is(':visible')) {
        $(this).popover('hide');
        $(this).next('.popover').remove();
      } else {
        $(this).popover('show'); /* show popover now it's setup */
      }
      e.preventDefault();
    }).on('shown.bs.popover', function (e) {
      /* Date range picker */
      $tr.find('.popover-content .datepicker-component').datepicker({
        format: 'yyyy-mm-dd'
      });

      var popover = $(this);

      /* Bind save */
      $(this).next().find('.popover-content .btn-save').on('click', function() {
        var date_start = $tr.find('.scenario-date-start').data('datepicker').getDate();
        var date_end = $tr.find('.scenario-date-end').data('datepicker').getDate();

        date_start = (isNaN(date_start.getFullYear())) ? '' : date_start.getFullYear() + '-' + (date_start.getMonth() + 1) + '-' + date_start.getDate();
        date_end = (isNaN(date_end.getFullYear())) ? '' : date_end.getFullYear() + '-' + (date_end.getMonth() + 1) + '-' + date_end.getDate();

        /* Set dates to hidden form */
        $tr.find('.date-start-end .scenario-date-start').attr('value', date_start);
        $tr.find('.date-start-end .scenario-date-end').attr('value', date_end);

        /* Close popover */
        $(popover).popover('hide'); $(popover).next('.popover').remove();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {sl : sl_scenario, name : 'datepicker-range', date_start: date_start, date_end: date_end},
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      });

    });
<?php
/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
?>


<?php
/*
 * -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * Time
 */
?>
    /* Set dates */
    var startTime = $tr.find('.scenario-time-start').val();
    var endTime = $tr.find('.scenario-time-end').val();
    if (startTime == '') startTime = '0:00';
    if (endTime == '') endTime = '23:59';

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

    timepicker_opts.defaultTime = startTime;

    $tr.find('.scenario-time-start').timepicker(timepicker_opts).on('changeTime.timepicker', $.debounce(800, function(e) {
      var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
      hours = (parseInt(hours) < 10) ? '0' + hours : hours;
      var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
      var time = hours + ':' + minutes + ':00';

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
        type: 'POST',
        data: {sl : sl_scenario, name : 'time-range-start', value: time, _token: '{{ csrf_token() }}'},
        dataType: 'json'
      });

      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
    }));

    timepicker_opts.defaultTime = endTime;

    $tr.find('.scenario-time-end').timepicker(timepicker_opts).on('changeTime.timepicker', $.debounce(800, function(e) {
      var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
      hours = (parseInt(hours) < 10) ? '0' + hours : hours;
      var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
      var time = hours + ':' + minutes + ':59';

      var request = $.ajax({
        url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
        type: 'POST',
        data: {sl : sl_scenario, name : 'time-range-end', value: time, _token: '{{ csrf_token() }}'},
        dataType: 'json'
      });

      request.done(function(json) { showSaved(); });
      request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
    }));

<?php
/*
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
?>
    /* Notification */
    var notification_content = $(this).find('.notification-content');
    var btn_notification = $(this).find('.btn-notification');

    $(btn_notification).popover({
      trigger: 'manual',
      placement:'right', 
      html : true, 
      content: function() { 
        return $(notification_content).html();
      }
    }).click(function(e) {
      if ($(this).next('.popover').is(':visible')) {
        $(this).popover('hide');
        $(this).next('.popover').remove();
      } else {
        $(this).popover('show'); /* show popover now it's setup */
      }
      e.preventDefault();
    }).on('shown.bs.popover', function (e) {

      $(this).data("bs.popover").tip().css({'max-width': '380px', 'width': '100%'});

      var popover = $(this);

      /* Bind save */
      $(this).next().find('.popover-content .btn-save').on('click', function() {

        /* Get values(s) from popover */
        var notification_title = $tr.find('.popover-content .notification_title').val();
        var notification = $tr.find('.popover-content .notification').val();

        /* Set value(s) to hidden form */
        $tr.find('.settings-content .notification_title').attr('value', notification_title);
        $tr.find('.settings-content .notification').text(notification);

        /* Close popover */
        $(popover).popover('hide'); $(popover).next('.popover').remove();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {sl : sl_scenario, name : 'notification', title: notification_title, value: notification},
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      });
    });

    /* Image */
    var img_content = $(this).find('.img-content');
    var btn_img = $(this).find('.btn-img');

    $(btn_img).popover({
      trigger: 'manual',
      placement:'right', 
      html : true, 
      content: function() { 
        return $(img_content).html();
      }
    }).click(function(e) {
      if ($(this).next('.popover').is(':visible')) {
        $(this).popover('hide');
        $(this).next('.popover').remove();
      } else {
        $(this).popover('show'); /* show popover now it's setup */
      }
      e.preventDefault();
    }).on('shown.bs.popover', function (e) {

      var popover = $(this);

      /* Bind save */
      $(this).next().find('.popover-content .btn-save').on('click', function() {

        /* Get values(s) from popover */
        var show_image = $tr.find('.popover-content .show-img').val();
        var show_image_thumb = $tr.find('.popover-content .thumbnail').attr('src');

        /* Set value(s) to hidden form */
        $tr.find('.settings-content .show-img').attr('value', show_image);
        if (typeof show_image_thumb === 'undefined') {
          $tr.find('.settings-content.img-content .show-image-container').html('');
        } else {
          $tr.find('.settings-content.img-content .show-image-container').html('<img src="' + show_image_thumb + '" class="thumbnail" style="max-width:100%;margin:10px 0 0 0;">');
        }

        /* Close popover */
        $(popover).popover('hide'); $(popover).next('.popover').remove();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {sl : sl_scenario, name : 'show_image', value: show_image },
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      });
      
    });

    /* Template */
    var tpl_content = $(this).find('.tpl-content');
    var btn_tpl = $(this).find('.btn-tpl');

    $(btn_tpl).on('click', function() {

      $.colorbox({
        href: app_root + '/scenarios/edit/template?i=' + $tr.attr('data-i'),
        fastIframe: true,
        overlayClose: false,
        iframe: true,
        width: '100%',
        transition: 'none', 
        fadeOut: 0,
        height: parseInt($(window).height()) + 42, 
        onOpen: function() {
          $(window).resize(colorBoxResizer);
          colorBoxResizer();
        },
        onLoad:function() {
          $('html, body').css('overflow', 'hidden'); // page scrollbars off
        }, 
        onClosed:function() {
          $(window).off("resize", colorBoxResizer);
          $('html, body').css('overflow', ''); // page scrollbars on
        }
      });
    });

    /* Url */
    var url_content = $(this).find('.url-content');
    var btn_url = $(this).find('.btn-url');

    $(btn_url).popover({
      trigger: 'manual',
      placement:'right', 
      html : true, 
      content: function() { 
        return $(url_content).html();
      }
    }).click(function(e) {
      if ($(this).next('.popover').is(':visible')) {
        $(this).popover('hide');
        $(this).next('.popover').remove();
      } else {
        $(this).popover('show'); /* show popover now it's setup */
      }
      e.preventDefault();
    }).on('shown.bs.popover', function (e) {

      var popover = $(this);

      $(this).data("bs.popover").tip().css({'max-width': '320px', 'width': '100%'});

      /* Bind save */
      $(this).next().find('.popover-content .btn-save').on('click', function() {

        /* Get values(s) from popover */
        var open_url = $tr.find('.popover-content .open-url').val();

        /* Set value(s) to hidden form */
        $tr.find('.settings-content .open-url').html(open_url);

        /* Close popover */
        $(popover).popover('hide'); $(popover).next('.popover').remove();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {sl : sl_scenario, name : 'open_url', value: open_url },
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      });

    });

    /* App image */
    var app_image_content = $(this).find('.app_image-content');
    var btn_app_image = $(this).find('.btn-app_image');

    $(btn_app_image).popover({
      trigger: 'manual',
      placement:'right', 
      html : true, 
      content: function() { 
        return $(app_image_content).html();
      }
    }).click(function(e) {
      if ($(this).next('.popover').is(':visible')) {
        $(this).popover('hide');
        $(this).next('.popover').remove();
      } else {
        $(this).popover('show'); /* show popover now it's setup */
      }
      e.preventDefault();
    }).on('shown.bs.popover', function (e) {

      var popover = $(this);

      /* Bind save */
      $(this).next().find('.popover-content .btn-save').on('click', function() {

        /* Get values(s) from popover */
        var app_image = $tr.find('.popover-content .app_image').val();
        var app_image_thumb = $tr.find('.popover-content .thumbnail').attr('src');

        /* Set value(s) to hidden form */
        $tr.find('.settings-content .app_image').attr('value', app_image);
        if (typeof app_image_thumb === 'undefined') {
          $tr.find('.settings-content.app_image-content .show-image-container').html('');
        } else {
          $tr.find('.settings-content.app_image-content .show-image-container').html('<img src="' + app_image_thumb + '" class="thumbnail" style="max-width:100%;margin:10px 0 0 0;">');
        }

        /* Close popover */
        $(popover).popover('hide'); $(popover).next('.popover').remove();

        var request = $.ajax({
          url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
          type: 'POST',
          data: {sl : sl_scenario, name : 'app_image', value: app_image },
          dataType: 'json'
        });

        request.done(function(json) { showSaved(); });
        request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
      });

    });

  });
}

function escapeHtml(str) {
  var div = document.createElement('div');
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
};

/* UNSAFE with unsafe strings; only use on previously-escaped ones! */
function unescapeHtml(escapedStr) {
  var div = document.createElement('div');
  div.innerHTML = escapedStr;
  var child = div.childNodes[0];
  return child ? child.nodeValue : '';
};

function saveTemplateEditor(i, content)
{
  $.colorbox.close();

  var $tr = $('#tbl-scenarios tr[data-i=' + i + ']');
     var sl_scenario = $tr.attr('data-sl');

  $tr.find('.settings-content .show-template').html(escapeHtml(content));

  var request = $.ajax({
    url: "{{ url('scenarios/update-scenario?token=' . $jwt_token) }}",
    type: 'POST',
    data: {sl : sl_scenario, name : 'template', value: content},
    dataType: 'json'
  });

  request.done(function(json) { showSaved(); });
  request.fail(function(jqXHR, textStatus) { alert('Request failed, please try again (' + textStatus + ')'); });
}

function getTemplateContent(i)

{
  var $tr = $('#tbl-scenarios tr[data-i=' + i + ']');
  var content = $tr.find('.settings-content .show-template').html();
  content = unescapeHtml(content);

  return content;
}

function checkScenarioIf($tr)
{
  closeAllPopovers();
   var scenario = $tr.find('.scenario-if'); 
  scenario = (scenario.hasClass('select2-container')) ? scenario.select2('val') : scenario.val();

  $tr.find('.btn-notification').hide();

  switch(parseInt(scenario))
  {
    case 1:
    case 2:
      $tr.find('.btn-notification').show();
      break;
  }
}

function checkScenarioThen($tr)
{
  closeAllPopovers();
   var scenario = $tr.find('.scenario-then'); 
  scenario = (scenario.hasClass('select2-container')) ? scenario.select2('val') : scenario.val();

  $tr.find('.btn-settings').hide();

  switch(parseInt(scenario))
  {
    case 2:
      $tr.find('.btn-img').show();
      break;
    case 3:
      $tr.find('.btn-tpl').show();
      break;
    case 4:
      $tr.find('.btn-url').show();
      break;
  }
}

function checkScenarioTime($tr)
{
  closeAllPopovers();
   var scenario = $tr.find('.scenario-time'); 
  scenario = (scenario.hasClass('select2-container')) ? scenario.select2('val') : scenario.val();

  $tr.find('.btn-time').hide();

  switch(parseInt(scenario))
  {
    case 2:
      $tr.find('.btn-time').show();
      break;
  }
}

function closeAllPopovers()
{
  $('.btn-popover').each(function () {
    if ($(this).next('div.popover:visible').length) {
      $(this).popover('hide');
      $(this).next('.popover').remove();
    }
  });
}

$('#tbl-scenarios').on('click', '.btn-delete', function() {
  var row = $(this).parents('tr');
  var sl_scenario = row.attr('data-sl');
  var request = $.ajax({
    url: "{{ url('scenarios/delete-scenario?token=' . $jwt_token) }}",
    type: 'POST',
     data: {sl : sl_scenario},
    dataType: 'json'
  });

  request.done(function(json) {
    row.remove();
    showSaved();
  });

  request.fail(function(jqXHR, textStatus) {
    alert('Request failed, please try again (' + textStatus + ')');
  });
});

var elfinderUrl = 'platform/media/picker/';

$('#tbl-scenarios').on('click', '.img-browse', function(event) {
  if(event.handled !== true) {

    $.colorbox({
      href: elfinderUrl + $(this).attr('data-id') + '/processBoardFile',
      fastIframe: true,
      overlayClose: false,
      iframe: true,
      width: '100%',
      transition: 'none', 
      fadeOut: 0,
      height: parseInt($(window).height()) + 42, 
      onOpen: function() {
        $(window).resize(colorBoxResizer);
      },
      onLoad:function() {
        $('html, body').css('overflow', 'hidden'); // page scrollbars off
      }, 
      onClosed:function() {
        $(window).off("resize", colorBoxResizer);
        $('html, body').css('overflow', ''); // page scrollbars on
      }
    });

    event.handled = true;
  }

  return false;
});

$('#tbl-scenarios').on('click', '.img-remove', function(event)
{
  if(event.handled !== true)
  {
    $('#' + $(this).attr('data-id') + '-image').html('');
    $('#' + $(this).attr('data-id')).val('');
    event.handled = true;
  }

  return false;
});

/* Callback after elfinder selection */
window.processBoardFile = function(filePath, requestingField) {
  if($('#' + requestingField).attr('type') == 'text') {
    $('#' + requestingField).val(decodeURI(filePath));
  }

  if($('#' + requestingField + '-image').length) {
    var img = decodeURI(filePath);
    var thumb = '{{ url('platform/thumbnail?') }}w=' + thumb_width + '&h=' + thumb_height + '&t=' + thumb_type + '&img=' + filePath;

    $('#' + requestingField + '-image').addClass('bg-loading');

    $('<img/>').attr('src', decodeURI(thumb)).load(function() {
      $(this).remove();
      $('#' + requestingField + '-image').html('<img src="' + thumb + '" class="thumbnail" style="max-width:100%; margin:10px 0 0 0;">');
      $('#' + requestingField + '-image').removeClass('bg-loading');
    });

    $('#' + requestingField).val(img);

    /* Reposition popover */
    //$('#' + requestingField).closest('.popover').css('top', '140px');
  }
};

// Open popup
openAppRequiredModal();

function openAppRequiredModal() {
  var dont_show_again = getCookie('dont_show_again');
  if(dont_show_again !== null && dont_show_again !== 'true'){
    $.colorbox({
      href: app_root + '/scenarios/app-required',
      fastIframe: true,
      overlayClose: false,
      iframe: true,
      width: 760,
      height: 590, 
      transition: 'none', 
      fadeOut: 0,
      onOpen: function() {
        $('#colorbox').attr('id', 'colorbox2');
      }, 
      onLoad: function() {
        $('html, body').css('overflow', 'hidden'); // page scrollbars off
      }, 
      onClosed:function() {
        $('html, body').css('overflow', ''); // page scrollbars on
      }
    });
  }
}
</script>