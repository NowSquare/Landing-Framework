@extends('layouts.platform')

@section('head')
<style type="text/css">
  html {
    background-position: left top;
    background-size: cover;
    background-image: url({{ url('assets/images/backgrounds/full01.jpg') }});
  }
  body {
    background: transparent;
  }
</style>
@endsection

@section('content')
<div class="wrapper-page">
  <div class="text-center">
    <a href="{{ url('/') }}" class="logo logo-lg"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo_square }}" style="height: 128px; margin: 2rem" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a>
  </div>
  <form class="form-horizontal m-t-20 mdl-shadow--2dp" role="form" method="POST" action="{{ url('/login') }}" style="background-color: rgba(255,255,255,1); padding: 3rem">
    @if(session()->has('error'))
    <div class="form-group">
      <div class="col-xs-12">
          <div class="alert alert-danger rounded-0" style="margin-bottom: 0">
              {{ session()->get('error') }}
          </div>
      </div>
    </div>
    @endif
    {{ csrf_field() }}
<?php
$email = (old('email') != '') ? old('email') : '';
$password = ''; 
if(config('app.demo'))
{
	if($email == '') $email = 'info@example.com';
	$password = 'welcome'; 

  $demo_txt = (env('DEMO_TXT_LOGIN', null)) ? env('DEMO_TXT_LOGIN') : '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . trans('global.login_demo_mode');

	echo '<div class="alert alert-warning">' . $demo_txt . '</div>';
}
?>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <div class="col-xs-12">
        <input id="email" type="email" class="form-control" name="email" value="{{ $email }}" placeholder="{{ trans('global.email') }}" required autofocus>
        <i class="material-icons form-control-feedback l-h-34">&#xE0BE;</i> @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
      <div class="col-md-12">
        <input id="password" type="password" class="form-control" name="password" value="{{ $password }}" placeholder="{{ trans('global.password') }}" required>
        <i class="material-icons form-control-feedback l-h-34">&#xE0DA;</i> @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif 
        <span class="help-block text-right"><a href="{{ url('/password/reset') }}" class="text-muted"><small>{{ trans('global.forgot_password') }}</small></a></span>
      </div>
    </div>
    <div class="form-group m-t-20 m-b-0">
      <div class="col-xs-6">
        <div class="checkbox checkbox-primary">
          <input name="remember" id="remember" type="checkbox" value="1">
          <label for="remember"> {{ trans('global.remember_me') }}</label>
        </div>
      </div>
      <div class="col-xs-6 text-right">
        <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit">{{ trans('global.log_in') }}</button>
      </div>
    </div>

<?php if (\Config::get('auth.allow_registration', true)) { ?>
    <div class="form-group m-t-30" style="margin-bottom: 0">
      <div class="col-12 text-center"> <a href="{{ url('/register') }}" class="text-muted" style="text-decoration: underline">{{ trans('global.create_account') }}</a> </div>
    </div>
<?php } ?>
  </form>
</div>
@endsection 