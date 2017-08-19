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
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_user') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/user/update') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <div class="col-md-8 col-lg-9">
        <ul class="nav nav-tabs navtab-custom">
          <li class="active"> <a href="#general" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.general') }}</span> </a> </li>
          <li> <a href="#localization" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-globe" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.localization') }}</span> </a> </li>
<?php if (\Gate::allows('owner-management')) { ?>
          <li> <a href="#role" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-globe" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.role') }}</span> </a> </li>
<?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="general">
            <fieldset>
<?php if (\Gate::allows('owner-management') || \Gate::allows('admin-management')) { ?>

<?php if (\Gate::allows('owner-management')) { ?>
            <div class="form-group">
<?php
                  $resellers_list = Former::select('reseller_id')
                    ->class('select2-required form-control')
                    ->name('reseller_id')
                    ->forceValue($user->reseller_id)
                    ->fromQuery($resellers, 'name', 'id')
                    ->label(trans('global.reseller'));

                  if ($user->id == 1 || $user->role == 'reseller' || $user->role == 'owner') $resellers_list->disabled(true);
                  echo $resellers_list;
?>
            </div>
<?php } ?>
<?php if (\Gate::allows('admin-management')) { ?>
              <hr>
            <div class="form-group">
<?php
                  $plans_list = Former::select('plan_id')
                    ->addOption(trans('global.free'), null)
                    ->class('select2-required form-control')
                    ->name('plan_id')
                    ->forceValue($user->plan_id)
                    ->options($plans_list)
                    ->label(trans('global.plan'));
 
                  echo $plans_list;
?>
            </div>
<?php } ?>
<?php } ?>

<?php if (\Gate::allows('owner-management')) { ?>
<?php
$field_name = 'trial_ends_at';
$datetime_value = $user->trial_ends_at;

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
$datetime_value = $user->expires;

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
              <hr>
              <div class="form-group">
                <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required autocomplete="off">
              </div>
              <div class="form-group">
                <label for="email">{{ trans('global.email_address') }} <sup>*</sup></label>
                <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required autocomplete="off">
              </div>
              <div class="form-group">
                <label for="new_password">{{ trans('global.new_password') }}</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="off">
                  <div class="input-group-btn add-on">
                    <button class="btn btn-inverse" type="button" id="show_password" data-toggle="tooltip" title="{{ trans('global.show_hide_password') }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <button class="btn btn-inverse" type="button" id="generate_password" data-toggle="tooltip" title="{{ trans('global.generate_password') }}"><i class="fa fa-random" aria-hidden="true"></i></button>
                  </div>
                </div>
              </div>
              <p class="text-muted">{{ trans('global.new_password_info') }}</p>
              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input name="mail_login" id="mail_login" type="checkbox" value="1">
                  <label for="mail_login"> {{ trans('global.mail_login_update') }}</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input name="active" id="active" type="checkbox" value="1"<?php if($user->active == 1) echo ' checked'; ?><?php if($user->id == 1) echo ' disabled'; ?>>
                  <label for="active"> {{ trans('global.active') }}</label>
                </div>
              </div>
              <p class="text-muted">{{ trans('global.active_user_desc') }}</p>
            </fieldset>
          </div>
          <div class="tab-pane" id="localization">
            <fieldset>
              <div class="form-group">
                <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($user->language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
              </div>
              <div class="form-group">
                <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($user->timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
              </div>
            </fieldset>
          </div>
<?php if (\Gate::allows('owner-management')) { ?>
          <div class="tab-pane" id="role">
            <fieldset>
              <div class="form-group">
                <?php
                $roles = Former::select('role')
                  ->class('select2-required form-control')
                  ->name('role')
                  ->forceValue($user->role)
                  ->label(trans('global.role'));
         
                if ($user->id == 1 || ! array_has(trans('global.user_roles'), $user->role)) {
                  
                  $roles->options(trans('global.roles'));
                  $roles->disabled();
                  
                } else {

                  $roles->options(trans('global.user_roles'));
                  
                }
                echo $roles;
                ?>
              </div>
            </fieldset>
          </div>
<?php } ?>
        </div>
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body"> <a href="#/admin/users" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
    </form>

    <div class="col-lg-3 col-md-4">
      <div class="text-center card-box" style="margin-top: 51px;">
        <div class="member-card">
          <div class="thumb-xl member-thumb m-b-10 center-block">
            <div style="display:none" class="dropzone-previews" id="dropzone-preview"></div>
            <img src="{{ $user->getAvatar() }}" class="img-circle img-thumbnail user-avatar" alt="profile-image" style="width:110px;height:110px">
          </div>
          <div>
            <h4 class="m-b-5">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
          </div>
          <a href="{{ url('platform/admin/user/login-as/' . $sl) }}" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">{{ trans('global.login_as_user') }}</a> <br>
          <button class="btn btn-warning btn-sm w-sm waves-effect m-t-10 waves-light" id="upload_avatar" type="button">{{ trans('global.upload_avatar') }}</button>
          <button class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light" id="remove_avatar" type="button">{{ trans('global.remove_avatar') }}</button>
          <script>
$('#upload_avatar').dropzone({ 
  url: '{{ url('platform/admin/user/upload-avatar') }}',
  maxFilesize: 3,
  headers: {
    'X-CSRF-Token': '{{ csrf_token() }}'
  },
  previewsContainer: '#dropzone-preview',
  acceptedFiles: 'image/*',
  sending: function(file, xhr, data) {
    data.append('sl', '{{ $sl }}');
    blockUI();
  },
  success : function(file, response) {
    $('.user-avatar').each(function() {
      $(this).attr('src', response + "?"+ new Date().getTime());
    });
  },
  complete: function() {
    unblockUI();
  },
});

$('#remove_avatar').on('click', function() {
  _confirm('{{ url('platform/admin/user/delete-avatar') }}', {_token: '{{ csrf_token() }}', sl: '{{ $sl }}'}, 'POST', function(ar1, ar2, json) {
    $('.user-avatar').each(function() {
      $(this).attr('src', json.src.encoded);
    });
  });
});
</script>
          <div class="text-left m-t-40">
            <table width="100%" class="table table-cutoff m-b-0">
              <tbody>
                <tr>
                  <td><strong>{{ trans('global.logins') }}:</strong></td>
                  <td>{{ $user->logins }}</td>
                </tr>
                <tr>
                  <td><strong>{{ trans('global.last_login') }}:</strong></td>
                  <td>{!! ($user->last_login != NULL) ? '<div data-moment="fromNowDateTime">' . $user->last_login->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s') . '</div>' : '' !!}</td>
                </tr>
                <tr>
                  <td><strong>{{ trans('global.last_ip') }}</strong></td>
                  <td>{{ $user->last_ip }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end card-box --> 
      
    </div>
    <!-- end col -->
    
  </div>
  <!-- end row --> 
  
</div>
<script>
  $('#show_password').on('click', function()
  {
    if(! $(this).hasClass('active'))
    {
      $(this).addClass('active');
      togglePassword('new_password', 'form-control', true);
    }
    else
    {
      $(this).removeClass('active');
      togglePassword('new_password', 'form-control', false);
    }
  });
  
  $('#generate_password').on('click', function()
  {
    $('#new_password').val(randomString(8));
  });    
</script>