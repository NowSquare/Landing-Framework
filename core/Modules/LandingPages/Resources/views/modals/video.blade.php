@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.video') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="form-group">
        <label for="src">{{ trans('landingpages::global.url') }}</label>
<?php /*

        <input type="text" class="form-control" id="src" name="src" autocomplete="off" value="">*/ ?>
        <div class="input-group" id="input-group-src">
          <input type="text" class="form-control" id="src" name="src" autocomplete="off" value="">
          <div class="input-group-btn add-on">
            <button type="button" class="btn btn-primary onClickParse">{{ trans('landingpages::global.parse') }}</button>
          </div>
        </div>
        <small class="help-block" id="src_help">{{ trans('landingpages::global.video_url_help') }}</small>
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

<?php /* ----------------------------------------------------------------------------
Set settings
*/ ?>

<?php if ($el_class != '') { ?>

  var $el = $('.{{ $el_class }}', window.parent.document);

  var src = $el.find('iframe').attr('src');
  $('#src').val(src);

<?php } ?>

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

<?php if ($el_class != '') { ?>

    $el.find('iframe').attr('src', $('#src').val());

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();
  });

  $('.onClickParse').on('click', function() {
    $('#input-group-src').removeClass('has-success has-error');
    var jqxhr = $.ajax({
      url: "{{ url('landingpages/editor/parse-embed') }}",
      data: {url: $('#src').val(),  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      blockUI();

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