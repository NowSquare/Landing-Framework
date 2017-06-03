@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('emailcampaigns::global.send_mailing') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-10 col-sm-6">

      <form class="ajax" id="frm" method="post" action="{{ url('emailcampaigns/editor/send-mailing') }}">
        <input type="hidden" name="sl" value="{{ $sl }}">
        {!! csrf_field() !!}

        <p class="lead">{{ trans('emailcampaigns::global.send_mailing_text') }}</p>
<?php
$total_members = 0;
if ($email->forms->count() > 0) { 
?>
        <ul class="m-b-30">
<?php
foreach ($email->forms as $form) { 
  $confirmed_members = \DB::table('x_form_entries_' . $form->user_id)->whereId($form->id)->where('confirmed', true)->count();
  $total_members += $confirmed_members;
?>
          <li>{{ $form->name }} ({{ trans('emailcampaigns::global.amount_confirmed_members', ['amount' => $confirmed_members]) }})</li>
<?php
}
?>
        </ul>
<?php 
}
?>
        <button type="button" class="btn btn-lg btn-block btn-primary btn-material ladda-button onClickSend" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi send"></i> {{ trans('emailcampaigns::global.send_now') }}</span></button>

        <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

        <div class="form-group">
          <div class="input-group input-group-lg" style="width: 100%">
            <input type="text" class="form-control datepicker-schedule" value="{{ \Carbon\Carbon::tomorrow(\Auth::user()->timezone)->format('Y-m-d') }}">
            <span class="input-group-addon b-0">@</span>
            <input type="text" class="form-control timepicker-component" value="{{ \Carbon\Carbon::now()->addHours(24)->timezone(\Auth::user()->timezone)->format('H:00') }}">
          </div>
        </div>


        <button type="button" class="btn btn-lg btn-block btn-primary btn-material ladda-button onClickSchedule" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi schedule"></i> {{ trans('emailcampaigns::global.schedule') }}</span></button>

      </form>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>

    </div>
  </div>
</div>

@endsection

@section('script')
<script>
$(function() {

  /* Date picker */
  $('.datepicker-schedule').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    todayHighlight: true,
    startDate: 'today',
    orientation: 'top'
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  });

  /* Time picker */
  $('.timepicker-component').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: '00:00'
  });

  $('.onClickSend').on('click', function() {

    blockUI();

    ladda_button = $(this).ladda();
    ladda_button.ladda('start');

    setTimeout(function() {
      ladda_button.ladda('stop');
      unblockUI();
    }, 3000);

  });

  $('.onClickSchedule').on('click', function() {

    blockUI();

    ladda_button = $(this).ladda();
    ladda_button.ladda('start');

    setTimeout(function() {
      ladda_button.ladda('stop');
      unblockUI();
    }, 3000);

  });

});
</script>
@endsection