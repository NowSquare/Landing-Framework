@extends('layouts.platform')

@section('content')
<div class="wrapper-page">
  <div class="text-center"> <a href="{{ url('/') }}" class="logo logo-lg"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo_square }}" style="height: 128px; margin: 2rem" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a> </div>
  <form class="form-horizontal m-t-20" role="form" method="POST" action="{{ url('/password/reset') }}">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <div class="col-xs-12">
        <input class="form-control" type="email" placeholder="{{ trans('global.email_address') }}" name="email" value="{{ $email or old('email') }}" required autofocus>
        <i class="material-icons form-control-feedback l-h-34">&#xE0BE;</i> @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
      <div class="col-xs-12">
        <input class="form-control" type="password" placeholder="{{ trans('global.password') }}" name="password" required>
        <i class="material-icons form-control-feedback l-h-34">&#xE0DA;</i> @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
    </div>
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
      <div class="col-xs-12">
        <input class="form-control" type="password" placeholder="{{ trans('global.confirm_password') }}" name="password_confirmation" required>
        <i class="material-icons form-control-feedback l-h-34">&#xE0DA;</i> @if ($errors->has('password_confirmation')) <span class="help-block"> <strong>{{ $errors->first('password_confirmation') }}</strong> </span> @endif </div>
    </div>
    <div class="form-group text-right m-t-20">
      <div class="col-xs-12">
        <button class="btn btn-primary btn-custom waves-effect waves-light w-md" type="submit">{{ trans('global.reset_password') }}</button>
      </div>
    </div>
  </form>
</div>
@endsection 