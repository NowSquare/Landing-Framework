@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('emailcampaigns::global.test_email') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <form class="ajax" id="frm" method="post" action="{{ url('emailcampaigns/editor/test-email') }}">
        <input type="hidden" name="sl" value="{{ $sl }}">
        {!! csrf_field() !!}

        <p class="lead">{{ trans('emailcampaigns::global.test_email_text') }}</p>

        <div class="form-group">
            <input type="text" class="form-control input-lg" id="mailto" name="mailto" autocomplete="off" value="<?php echo ($email->last_test_email != '') ? $email->last_test_email : auth()->user()->email; ?>">
        </div>

        <button type="submit" class="btn btn-lg btn-primary btn-material ladda-button onClickSend" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi send"></i> {{ trans('emailcampaigns::global.send_email') }}</span></button>
      </form>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>

    </div>
  </div>
</div>
@endsection