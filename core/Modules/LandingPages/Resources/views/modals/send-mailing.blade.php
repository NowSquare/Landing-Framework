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
  if ($total_members == 0) {
    echo '<div class="alert alert-warning" role="alert"><strong>' . trans('emailcampaigns::global.no_confirmed_members') . '</strong></div>';
  }
} else {
  echo '<div class="alert alert-warning" role="alert"><strong>' . trans('emailcampaigns::global.no_lists_selected') . '</strong></div>';
}

if ($total_members > 0) {
?>
        <button type="button" class="btn btn-lg btn-block btn-primary btn-material ladda-button onClickSend" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi send"></i> <?php echo ($scheduled) ? trans('emailcampaigns::global.send_now') . ' &amp; ' . trans('emailcampaigns::global.remove_schedule') : trans('emailcampaigns::global.send_now'); ?></span></button>

        <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

        <div class="form-group">
          <div class="input-group input-group-lg" style="width: 100%">
            <input type="text" class="form-control" id="scheduled_at_date" value="{{ \Carbon\Carbon::parse($scheduled_at)->format('D M jS Y') }}" data-value="{{ \Carbon\Carbon::parse($scheduled_at)->format('Y-m-d') }}">
            <span class="input-group-addon b-0">@</span>
            <input type="text" class="form-control" id="scheduled_at_time" value="{{ \Carbon\Carbon::parse($scheduled_at)->format('H:i') }}" data-value="{{ \Carbon\Carbon::parse($scheduled_at)->format('H:i') }}">
          </div>
        </div>

        <button type="button" class="btn btn-lg btn-block btn-primary btn-material ladda-button onClickSchedule" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi schedule"></i> <?php echo ($scheduled) ? trans('emailcampaigns::global.update_schedule') : trans('emailcampaigns::global.schedule'); ?></span></button>
<?php if ($scheduled) { ?>
        <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

        <button type="button" class="btn btn-lg btn-block btn-danger btn-material ladda-button onClickRemoveSchedule" data-style="zoom-in" data-spinner-color="#fff"><span class="ladda-label"><i class="mi remove_circle_outline"></i> <?php echo trans('emailcampaigns::global.remove_schedule'); ?></span></button>
<?php } ?>
      </form>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>

    </div>
  </div>
</div>
<?php
}
?>
@endsection

@section('script')
<?php if ($total_members > 0) { ?>
<script>
$(function() {

  /* Date picker */
  $('#scheduled_at_date').datepicker({
    autoclose: true,
    /*format: "yyyy-mm-dd",*/
    todayHighlight: true,
    /*startDate: moment().subtract(1, 'days').format('YYYY-MM-D'),*/
    orientation: 'top',
    format: {
      toDisplay: function (date, format, language) {
        $('#scheduled_at_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($('#scheduled_at_date').attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  });

  /* Time picker */
  $('#scheduled_at_time').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: '00:00'
  }).on('changeTime.timepicker', function(e) {
    var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes;
    
    $('#scheduled_at_time').attr('data-value', time);
  });

  $('.onClickSend').on('click', function() {

    blockUI();

    ladda_button = $(this).ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
      url: "{{ url('emailcampaigns/send-mailing') }}",
      data: {sl: "{{ $sl }}", _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      swal({
        type: data.type,
        title: data.msg,
        confirmButtonText: '{{ trans('javascript.ok') }}',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false
      }).then(function () {
        window.parent.lfCloseModal();
      }, function (dismiss) {
        window.parent.lfCloseModal();
      });

    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
      unblockUI();
    });

  });

  $('.onClickSchedule').on('click', function() {

    blockUI();

    ladda_button = $(this).ladda();
    ladda_button.ladda('start');

    var scheduled_at_date = $('#scheduled_at_date').attr('data-value');
    var scheduled_at_time = $('#scheduled_at_time').attr('data-value');
    var scheduled_at = scheduled_at_date + ' ' + scheduled_at_time + ':00';

    var jqxhr = $.ajax({
      url: "{{ url('emailcampaigns/schedule-mailing') }}",
      data: {scheduled_at: scheduled_at, sl: "{{ $sl }}", _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      swal({
        type: data.type,
        title: data.msg,
        confirmButtonText: '{{ trans('javascript.ok') }}',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false
      }).then(function () {
        window.parent.lfCloseModal();
      }, function (dismiss) {
        window.parent.lfCloseModal();
      });

    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
      unblockUI();
    });

  });

  $('.onClickRemoveSchedule').on('click', function() {

    blockUI();

    ladda_button = $(this).ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
      url: "{{ url('emailcampaigns/remove-schedule-mailing') }}",
      data: {sl: "{{ $sl }}", _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      swal({
        type: data.type,
        title: data.msg,
        confirmButtonText: '{{ trans('javascript.ok') }}',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false
      }).then(function () {
        window.parent.lfCloseModal();
      }, function (dismiss) {
        window.parent.lfCloseModal();
      });

    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
      unblockUI();
    });

  });

});
</script>
<?php } ?>
@endsection