@extends('layouts.platform')

@section('head') 
<script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart', 'line']});
</script>
@endsection 

@section('content') 
<header id="topnav">
  <div class="topbar-main">
    <div class="container"> 

      <div class="logo">
        <a href="#/"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo }}" style="height: 32px" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a>
      </div>

      <div id="navigation">
        <ul class="navigation-menu">
<?php /*          <li class="has-submenu"><a href="#/" class="waves-effect waves-light">{{ trans('global.dashboard') }}</a></li>*/ ?>
<?php
foreach ($active_modules as $module) {
?>
          <li class="has-submenu" id="module{{ $module['namespace'] }}"><a href="#/{{ $module['namespace'] }}" class="waves-effect waves-light">{{ $module['name_plural'] }}</a></li>
<?php } ?>
<?php if (1==2 && Gate::allows('limitation', 'media.visible')) { ?>
          <li class="has-submenu"><a href="#/media" class="waves-effect waves-light">{{ trans('global.media') }}</a></li>
<?php } ?>
<?php /*
          <li class="has-submenu"> <a href="#/profile" class="waves-effect waves-light">{{ trans('global.account') }}</a></li>
*/ ?>
<?php /*
<?php if (Gate::allows('admin-management')) { ?>
          <li class="has-submenu last-elements"> <a href="javascript:void(0);" class="no-link waves-effect waves-light">{{ trans('global.admin') }}</a>
            <ul class="submenu">
              <li class="has-submenu">
                <a href="javascript:void(0);">{{ trans('global.users') }}</a>
                <ul class="submenu">
                  <li><a href="#/admin/users">{{ trans('global.users') }}</a></li>
                  <li><a href="#/admin/plans">{{ trans('global.plans') }}</a></li>
<?php if (Gate::allows('owner-management')) { ?>
                  <li role="separator" class="divider"><hr></li>
                  <li><a href="#/admin/resellers">{{ trans('global.resellers') }}</a></li>
<?php } ?>
                </ul>
              </li>

            </ul>
          </li>
<?php } ?>
*/ ?>
        </ul>
      </div>

      <div class="menu-item"> 
        <a class="navbar-toggle">
        <div class="lines">
          <span></span>
          <span></span>
          <span></span>
        </div>
        </a> 
      </div>

      <div class="menu-extras">
        <ul class="nav navbar-nav navbar-right pull-right">
          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="{{ \Auth::user()->getAvatar() }}" class="img-circle avatar"> </a>
            <ul class="dropdown-menu">
              <li class="dropdown-header" style="font-size: 1.5rem">{{ \Auth::user()->name }}</li>
              <li class="dropdown-header text-muted">{{ \Auth::user()->email }}</li>
              <li role="separator" class="divider"><hr></li>
              <li><a href="#/profile"><i class="mi account_circle m-r-5"></i> {{ trans('global.profile') }}</a></li>
<?php if (Gate::allows('limitation', 'account.plan_visible')) { ?>
              <li><a href="#/plan"><i class="mi credit_card m-r-5"></i> {{ trans('global.plan') }}</a></li>
<?php } ?>
<?php if (Gate::allows('admin-management')) { ?>
              <li role="separator" class="divider"><hr></li>
              <li class="dropdown-header text-muted">{{ trans('global.admin') }}</li>
              <li><a href="#/admin/users"><i class="mi people m-r-5"></i> {{ trans('global.users') }}</a></li>
              <li><a href="#/admin/plans"><i class="mi card_membership m-r-5"></i> {{ trans('global.plans') }}</a></li>
<?php if (Gate::allows('owner-management')) { ?>
              <li><a href="#/admin/resellers"><i class="mi card_travel m-r-5"></i> {{ trans('global.resellers') }}</a></li>
<?php } ?>
<?php } ?>
              <li role="separator" class="divider"><hr></li>
              <li><a href="{{ url('logout') }}"><i class="mi power_settings_new m-r-5"></i> {{ trans('global.logout') }}</a></li>
            </ul>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right pull-right" id="device_selector">
          <li class="menu-icon-button active" id="desktop_mode"><a href="javascript:void(0);"><i class="material-icons">&#xE30C;</i></a></li>
          <li class="menu-icon-button" id="tablet_mode"><a href="javascript:void(0);"><i class="material-icons">&#xE330;</i></a></li>
          <li class="menu-icon-button" id="phone_mode"><a href="javascript:void(0);"><i class="material-icons">&#xE32C;</i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right pull-right" id="generic">
          <li id="generic_title"><a href="javascript:void(0);" class="no-link" style="color:#ddd !important; font-size: 1.8rem;"></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right pull-right funnel_selector">
          <li id="funnel_selector">
            <div class="navbar-left app-search pull-left hidden-xs" style="min-width:220px">
              <select id="selected_funnel" class="select2-topnav">
                <optgroup label="{{ trans('global.funnels') }}">
<?php
$selected_funnel = '';
foreach($funnels as $funnel) { 
  $sl_funnel = \Platform\Controllers\Core\Secure::array2string(['funnel_id' => $funnel->id]);
  $selected = ($funnel_id == $funnel->id) ? ' selected' : '';
  if ($selected != '') $selected_funnel = $sl_funnel;
?>
                  <option value="{{ $sl_funnel }}"{{ $selected }}>{{ $funnel->name }}</option>
<?php } ?>
                </optgroup>
                <optgroup label="{{ trans('global.options') }}">
                <option value="-2">{{ trans('global.manage_funnels') }}</option>
                <option value="-1">{{ trans('global.create_funnel') }}</option>
                </optgroup>
              </select>

            </div>
          </li>
<?php
// Only show language dropdown if there's more than one language available
if (count($languages) > 1) {
?>
          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">{{ $current_language }} </a>
            <ul class="dropdown-menu">
<?php foreach($languages as $code => $language) { ?>
              <li><a href="{{ url('platform?lang=' . $code) }}">{{ $language }}</a></li>
<?php } ?>
            </ul>
          </li>
<?php } ?>
        </ul>
      </div>
    </div>
  </div>

</header>
<div class="wrapper">
  <section id="view">
  </section>
</div>
@endsection 

@section('bottom')
<script>
var funnel_count = {{ $funnels->count() }};
var selected_funnel = "{{ $selected_funnel }}";

$(function() {

  if (funnel_count == 0) {
    createFunnel(true);
  }

  $('.select2-topnav').select2({
    allowClear: false,
    templateResult: function(result) {
      if (!result.id) return result.text;

      if (result.id == -1) {
        return $('<span><i class="mi add"></i> ' + result.text + '</span>');
      } else if (result.id == -2) {
        return $('<span><i class="mi view_headline"></i> ' + result.text + '</span>');
      } else {
        return $('<span><i class="mi filter_list"></i> ' + result.text + '</span>');
        //return result.text;
      }
    },
    templateSelection: function(result) {
      if (!result.id) return result.text;

      if (result.id == -1) {
        return $('<span><i class="mi add"></i> ' + result.text + '</span>');
      } else if (result.id == -2) {
        return $('<span><i class="mi view_headline"></i> ' + result.text + '</span>');
      } else {
        return $('<span><i class="mi filter_list"></i> ' + result.text + '</span>');
        //return result.text;
      }
    }
  });

  $('#selected_funnel').on('change', function() {
    var funnel = $(this).val();

    if (funnel == -1) {
      createFunnel(false);
    } else 
    if (funnel == -2) {
      document.location = '#/funnels';
      $('#selected_funnel').val(selected_funnel).trigger('change.select2');
    } else {
      selectFunnel(funnel);
    }
  });

<?php if (1==2 && \Auth::user()->logins <= 1) { ?>

$.notify({
  title: "{{ trans('global.hi.' . mt_rand(0, count(trans('global.hi')) - 1)) }}",
  text: "{{ trans('global.welcome_name', ['name' => \Auth::user()->name]) }}",
  image: "<i class='fa fa-smile-o'></i>"
}, {
  style: 'metro',
  className: 'success',
  globalPosition: 'top right',
  showAnimation: "show",
  showDuration: 0,
  hideDuration: 0,
  autoHide: false,
  clickToHide: true
});
<?php } ?>

});

/**
 * Select funnel
 */

function selectFunnel(funnel) {
  blockUI();

  var jqxhr = $.ajax({
    url: "{{ url('platform/funnels/select') }}",
    data: {sl_funnel: funnel, _token: '<?= csrf_token() ?>'},
    method: 'POST'
  })
  .done(function(data) {
    if (typeof data.redir !== 'undefined') {
      document.location.reload();
    } else if (typeof data.msg !== 'undefined') {
      swal(
        "{{ trans('global.oops') }}",
        data.msg,
        'error'
      )
    }
  })
  .fail(function() {
    console.log('error');
  })
  .always(function() {
    unblockUI();
  });
}

/**
 * Create new funnel
 */

function createFunnel(first_funnel) {
  first_funnel = (typeof first_funnel === 'undefined') ? false : first_funnel;
  var showCancelButton = (first_funnel) ? false : true;
  var text = (first_funnel) ? "{{ trans('global.create_first_funnel_text') }}" : "{{ trans('global.create_funnel_text') }}";

  swal({
    title: '{{ trans('global.create_funnel') }}',
    text: text,
    input: 'text',
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: true,
    showCancelButton: showCancelButton,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#138dfa",
    confirmButtonText: _lang['ok'],
    inputValidator: function (value) {
      return new Promise(function (resolve, reject) {
        if (value) {
          resolve()
        } else {
          reject('{{ trans('global.please_enter_value') }}')
        }
      })
    }
  }).then(function (result) {

    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/funnels/new') }}",
      data: {name: result, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if (typeof data.redir !== 'undefined') {
        if (document.location.hash == '#/' || document.location.hash == '#/funnels' || data.redir == 'reload') {
          document.location.reload();
        } else {
          document.location.reload();
          //document.location = data.redir;
        }
      } else if (typeof data.msg !== 'undefined') {
        swal(
          "{{ trans('global.oops') }}",
          data.msg,
          'error'
        )
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });

  }).catch(function(dismiss) {
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
    if (dismiss == 'cancel') {
      $('#selected_funnel').val(selected_funnel).trigger('change.select2');
    }
  });
}
</script>
@endsection 