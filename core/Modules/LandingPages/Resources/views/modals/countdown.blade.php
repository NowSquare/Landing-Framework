@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.countdown') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-9">

      <div class="form-group" id="input-group-src">
        <label for="template">{{ trans('landingpages::global.template') }}</label>
        <input type="text" class="form-control input-lg" id="template" name="template" autocomplete="off" value="">
        <small class="help-block text-muted">{{ trans('landingpages::global.template_help') }}</small>
      </div>

      <div class="form-group">
        <label for="template">{{ trans('landingpages::global.countdown_to') }}</label>
        <div class="input-group input-group-lg" style="width: 100%">
          <input type="text" class="form-control" id="countdown_to_date" value="" data-value="">
          <span class="input-group-addon b-0">@</span>
          <input type="text" class="form-control" id="countdown_to_time" value="" data-value="">
        </div>
      </div>

      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.cancel') }}</button>
        <button type="button" class="btn btn-primary btn-material onClickUpdate">{{ trans('global.update') }}</button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('script') 
<script>
$(function() {

  /* Date picker */
  $('#countdown_to_date').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: {
      toDisplay: function (date, format, language) {
        $('#countdown_to_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($('#countdown_to_date').attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  });

  /* Time picker */
  $('#countdown_to_time').timepicker({
    minuteStep: 1,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: '00:00'
  }).on('changeTime.timepicker', function(e) {
    var hours = e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes + ' ' + e.time.meridian;
    
    $('#countdown_to_time').attr('data-value', time);
  });

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  var template = $el.html();
  template = template.replace(/<span class="day">(.+?)<\/span>/, '{day}');
  template = template.replace(/<span class="hour">(.+?)<\/span>/, '{hour}');
  template = template.replace(/<span class="minute">(.+?)<\/span>/, '{minute}');
  template = template.replace(/<span class="second">(.+?)<\/span>/, '{second}');

  $('#template').val($.trim(template));

  var countdown_to = $el.attr('data-countdown');

  $('#countdown_to_date').datepicker('setDate', moment.tz(countdown_to, 'UTC').tz('{{ \Auth::user()->timezone }}').toDate());
  $('#countdown_to_date').datepicker('update');
  $('#countdown_to_time').timepicker('setTime', moment.tz(countdown_to, 'UTC').tz('{{ \Auth::user()->timezone }}').toDate());
  $('#countdown_to_time').timepicker('update');

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

<?php if ($el_class != '') { ?>

    var template = $('#template').val();
    template = template.replace(/{day}/, '<span class="day">_</span>');
    template = template.replace(/{hour}/, '<span class="hour">__</span>');
    template = template.replace(/{minute}/, '<span class="minute">__</span>');
    template = template.replace(/{second}/, '<span class="second">__</span>');

    $el.html(template);

    var countdown_to_date = $('#countdown_to_date').attr('data-value');
    var countdown_to_time = $('#countdown_to_time').attr('data-value');
    var countdown_to = countdown_to_date + ' ' + countdown_to_time;

    countdown_to = moment(countdown_to).tz('UTC').format('YYYY-MM-DD H:mm:00');

    $el.attr('data-countdown', countdown_to);
    window.parent.bindCountdown($el);

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });

  $('.onClickParse').on('click', function() {
    blockUI();

    $('#input-group-src').removeClass('has-success has-error');
    var jqxhr = $.ajax({
      url: "{{ url('landingpages/editor/parse-embed') }}",
      data: {url: $('#src').val(),  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if (! data.success) {
        $('#input-group-src').addClass('has-error');
        $('#src_help').html(data.msg);
      } else {
        $('#input-group-src').addClass('has-success');
        $('#src').val(data.url)
        $('#src_help').html(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });

  });
});
</script>
@endsection