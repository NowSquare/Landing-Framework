@extends('layouts.platform')

@section('content') 
<header id="topnav">
  <div class="topbar-main">
    <div class="container"> 

      <div class="logo">
        <a href="#/"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo }}" style="height: 32px" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a>
      </div>


      <div id="navigation">
        <ul class="navigation-menu">
           <li class="has-submenu" id="tour-dashboard"><a href="#/">{{ trans('global.dashboard') }}</a></li>
            <li class="has-submenu"><a href="#/create">{{ trans('global.create') }}</a></li>
            <li class="has-submenu"><a href="#/landing/editor">{{ trans('global.editor') }}</a></li>
<?php if (1==2 && Gate::allows('limitation', 'media.visible')) { ?>
          <li class="has-submenu"><a href="#/media">{{ trans('global.media') }}</a></li>
<?php } ?>
          <li class="has-submenu"> <a href="#/profile">{{ trans('global.account') }}</a></li>

<?php if (Gate::allows('admin-management')) { ?>
          <li class="has-submenu last-elements"> <a href="javascript:void(0);">{{ trans('global.admin') }}</a>
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
              <li><a href="#/profile"><i class="ti-user m-r-5"></i> {{ trans('global.profile') }}</a></li>
<?php if (Gate::allows('limitation', 'account.plan_visible')) { ?>
              <li><a href="#/plan"><i class="ti-crown m-r-5"></i> {{ trans('global.plan') }}</a></li>
<?php } ?>
              <li role="separator" class="divider"><hr></li>
              <li><a href="{{ url('logout') }}"><i class="ti-power-off m-r-5"></i> {{ trans('global.logout') }}</a></li>
            </ul>
          </li>
        </ul>
<?php
// Only show language dropdown if there's more than one language available
if (count($languages) > 1) {
?>
        <ul class="nav navbar-nav navbar-right pull-right">
          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">{{ $current_language }} </a>
            <ul class="dropdown-menu">
<?php foreach($languages as $code => $language) { ?>
              <li><a href="{{ url('platform?lang=' . $code) }}">{{ $language }}</a></li>
<?php } ?>
            </ul>
          </li>
        </ul>
<?php } ?>
      </div>
    </div>
  </div>

</header>
<div class="wrapper">
  <section id="view">
  </section>
</div>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_KEY') }}&libraries=places,visualization">
</script>
@endsection 

@section('bottom')
<?php if (\Auth::user()->logins <= 1) { ?>
<script>
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
</script>
<?php } ?>
@endsection 