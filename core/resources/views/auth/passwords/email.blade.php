@extends('layouts.platform') 

<!-- Main Content --> 
@section('content')
<div class="wrapper-page">
  <div class="text-center">
    <a href="{{ url('/') }}" class="logo logo-lg"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo_square }}" style="height: 128px; margin: 2rem" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a>
  </div>
  <form class="text-center m-t-20 mdl-shadow--2dp" role="form" method="POST" action="{{ url('/password/email') }}" style="background-color: rgba(255,255,255,1); padding: 3rem">
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
    
    @if (session('status'))
    <div class="alert alert-success"> {{ session('status') }} </div>
    @else
    <p style="text-align: left; margin-bottom: 20px"> {{ trans('global.reset_password_info') }} </p>
    @endif
    <div class="form-group m-b-0{{ $errors->has('email') ? ' has-error' : '' }}">
      <div class="input-group">
        <input type="email" class="form-control" placeholder="{{ trans('global.enter_email') }}" name="email" value="{{ old('email') }}" required>
        <i class="material-icons form-control-feedback l-h-34" style="left:6px;z-index: 99;">&#xE0BE;</i> <span class="input-group-btn">
        <button type="submit" class="btn btn-email btn-primary waves-effect waves-light">{{ trans('global.reset') }}</button>
        </span>
      </div>
      @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif
    </div>
    <div class="form-group m-t-30" style="margin-bottom: 40px">
      <div class="col-sm-6 text-left"> <a href="{{ url('/login') }}" class="text-muted"><i class="fa fa-sign-in m-r-5"></i> {{ trans('global.log_in') }}</a> </div>
      <div class="col-sm-6 text-right"> <a href="{{ url('/register') }}" class="text-muted">{{ trans('global.create_account') }}</a> </div>
    </div>
  </form>
</div>
@endsection 