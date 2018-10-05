<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/admin/users">{{ trans('global.users') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.create_user') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/user/new') }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
<?php if (\Gate::allows('owner-management') || \Gate::allows('admin-management')) { ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.reseller') }}</h3>
          </div>
          <fieldset class="panel-body">
<?php if (\Gate::allows('owner-management')) { ?>
            <div class="form-group">
<?php
                  $resellers_list = Former::select('reseller_id')
                    ->class('select2-required form-control')
                    ->name('reseller_id')
                    ->forceValue(\Platform\Controllers\Core\Reseller::get()->id)
                    ->fromQuery($resellers, 'name', 'id')
                    ->label('');
 
                  echo $resellers_list;
?>
            </div>
<?php } ?>
<?php if (\Gate::allows('admin-management')) { ?>
            <div class="form-group">
<?php
                  $plans_list = Former::select('plan_id')
                    ->addOption(trans('global.free'), null)
                    ->class('select2-required form-control')
                    ->name('plan_id')
                    ->options($plans_list)
                    ->forceValue($default_plan->id)
                    ->label(trans('global.plan'));
 
                  echo $plans_list;
?>
            </div>
<?php } ?>

<?php if (\Gate::allows('owner-management')) { ?>
<?php
$field_name = 'trial_ends_at';
$datetime_value = \Carbon\Carbon::now()->addDays(14)->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');

$date_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('D M jS Y');
$date_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('Y-m-d');

$time_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
$time_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
?>
              <div class="form-group">
                <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $datetime_value }}">
                <label>{{ trans('global.trial_expires') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.trial_expires_help') }}">&#xE887;</i></label>
                <div class="input-group" style="width: 100%">
                  <input type="text" class="form-control" id="{{ $field_name }}_date" value="{{ $date_value }}" data-value="{{ $date_data_value }}">
                  <span class="input-group-addon b-0">@</span>
                  <input type="text" class="form-control" id="{{ $field_name }}_time" value="{{ $time_value }}" data-value="{{ $time_data_value }}">
                </div>
              </div>

<script>
  /* Date picker */
  $('#{{ $field_name }}_date').datepicker({
    autoclose: true,
    todayHighlight: true,
    orientation: 'top',
    format: {
      toDisplay: function (date, format, language) {
        $('#{{ $field_name }}_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($(this).attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  }).on('changeDate', function(e) {
    setDate{{ $field_name }}();
  });

  /* Time picker */
  $('#{{ $field_name }}_time').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: null
  }).on('changeTime.timepicker', function(e) {
    var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes;

    $('#{{ $field_name }}_time').attr('data-value', time);

    setDate{{ $field_name }}();
  });

  $('#{{ $field_name }}_date,#{{ $field_name }}_time').on('change', function() {
    if ($('#{{ $field_name }}_date').val() == '' && $('#{{ $field_name }}_time').val() == '') $('#{{ $field_name }}').val('');
  });

  function setDate{{ $field_name }}() {
    if ($('#{{ $field_name }}_date').attr('data-value') != '' && $('#{{ $field_name }}_time').attr('data-value') != '') {
      $('#{{ $field_name }}').val($('#{{ $field_name }}_date').attr('data-value') + ' ' + $('#{{ $field_name }}_time').attr('data-value') + ':00');
    }
  }
</script>



<?php
$field_name = 'expires';
$datetime_value = '';

$date_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('D M jS Y');
$date_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('Y-m-d');

$time_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
$time_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
?>
              <div class="form-group">
                <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $datetime_value }}">
                <label>{{ trans('global.subscription_expires') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.subscription_expires_help') }}">&#xE887;</i></label>
                <div class="input-group" style="width: 100%">
                  <input type="text" class="form-control" id="{{ $field_name }}_date" value="{{ $date_value }}" data-value="{{ $date_data_value }}">
                  <span class="input-group-addon b-0">@</span>
                  <input type="text" class="form-control" id="{{ $field_name }}_time" value="{{ $time_value }}" data-value="{{ $time_data_value }}">
                </div>
              </div>

<script>
  /* Date picker */
  $('#{{ $field_name }}_date').datepicker({
    autoclose: true,
    todayHighlight: true,
    orientation: 'top',
    format: {
      toDisplay: function (date, format, language) {
        $('#{{ $field_name }}_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($(this).attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  }).on('changeDate', function(e) {
    setDate{{ $field_name }}();
  });

  /* Time picker */
  $('#{{ $field_name }}_time').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: null
  }).on('changeTime.timepicker', function(e) {
    var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes;

    $('#{{ $field_name }}_time').attr('data-value', time);

    setDate{{ $field_name }}();
  });

  $('#{{ $field_name }}_date,#{{ $field_name }}_time').on('change', function() {
    if ($('#{{ $field_name }}_date').val() == '' && $('#{{ $field_name }}_time').val() == '') $('#{{ $field_name }}').val('');
  });

  function setDate{{ $field_name }}() {
    if ($('#{{ $field_name }}_date').attr('data-value') != '' && $('#{{ $field_name }}_time').attr('data-value') != '') {
      $('#{{ $field_name }}').val($('#{{ $field_name }}_date').attr('data-value') + ' ' + $('#{{ $field_name }}_time').attr('data-value') + ':00');
    }
  }
</script>

<?php } ?>
          </fieldset>
        </div>
<?php } ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.general') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="email">{{ trans('global.email_address') }} <sup>*</sup></label>
              <input type="email" class="form-control" name="email" id="email" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="password">{{ trans('global.password') }} <sup>*</sup></label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
                <div class="input-group-btn add-on">
                  <button class="btn btn-inverse" type="button" id="show_password" data-toggle="tooltip" title="{{ trans('global.show_hide_password') }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                  <button class="btn btn-inverse" type="button" id="generate_password" data-toggle="tooltip" title="{{ trans('global.generate_password') }}"><i class="fa fa-random" aria-hidden="true"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="mail_login" id="mail_login" type="checkbox" value="1" checked>
                <label for="mail_login"> {{ trans('global.mail_login') }}</label>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
            <p class="text-muted">{{ trans('global.active_user_desc') }}</p>
          </fieldset>
        </div>

        <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/admin/users" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.role') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
<?php
                  $roles = Former::select('role')
                    ->class('select2-required form-control')
                    ->name('role')
                    ->forceValue('user')
                    ->options(trans('global.user_roles'))
                    ->label('');
 
                  echo $roles;
?>
            </div>
          </fieldset>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.localization') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($reseller->default_language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
            </div>
            <div class="form-group">
              <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($reseller->default_timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- end col -->
      
    </form>
  </div>
  <!-- end row --> 
  
</div>
<script>
  $('#show_password').on('click', function()
  {
    if(! $(this).hasClass('active'))
    {
      $(this).addClass('active');
      togglePassword('password', 'form-control', true);
    }
    else
    {
      $(this).removeClass('active');
      togglePassword('password', 'form-control', false);
    }
  });
  
  $('#generate_password').on('click', function()
  {
    $('#password').val(randomString(8));
  });    
</script>