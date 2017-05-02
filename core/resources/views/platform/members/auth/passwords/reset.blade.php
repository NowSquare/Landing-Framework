<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ trans('global.reset_password') }}</title>
<link href="{{ url('templates/assets/css/style.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="text-xs-center m-y-3">{{ trans('global.reset_password') }}</h1>
      <form class="form form-horizontal flat-form" role="form" method="POST" action="{{ url('member/password/reset') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="sl" value="{{ $sl }}">
        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
          <div class="input-group"> <span class="input-group-addon"><i class="material-icons">&#xE0BE;</i></span>
            <input type="email" class="form-control" placeholder="{{ trans('global.email_address') }}" name="email" value="{{ $email or old('email') }}" required autofocus>
          </div>
          @if ($errors->has('email')) <span class="form-control-feedback"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
          <div class="input-group"> <span class="input-group-addon"><i class="material-icons">&#xE0DA;</i></span>
            <input type="password" class="form-control" placeholder="{{ trans('global.password') }}" name="password" required>
          </div>
          @if ($errors->has('password')) <span class="form-control-feedback"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
          <div class="input-group"> <span class="input-group-addon"><i class="material-icons">&#xE0DA;</i></span>
            <input type="password" class="form-control" placeholder="{{ trans('global.confirm_password') }}" name="password_confirmation" required>
          </div>
          @if ($errors->has('password_confirmation')) <span class="form-control-feedback"> <strong>{{ $errors->first('password_confirmation') }}</strong> </span> @endif </div>
        <div class="form-group">

            <button class="btn btn-primary btn-lg" type="submit">{{ trans('global.reset_password') }}</button>

        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('templates/assets/js/scripts.min.js') }}"></script>
</body></html>