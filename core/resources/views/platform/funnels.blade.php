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
          <li class="has-submenu"><a href="#/funnels" class="waves-effect waves-light">{{ trans('global.funnels') }}</a></li>
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

        <ul class="nav navbar-nav navbar-right pull-right">
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