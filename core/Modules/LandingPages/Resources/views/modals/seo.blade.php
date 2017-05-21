@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.seo') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <div class="form-group">
            <label for="name">{{ trans('landingpages::global.name') }}</label>
              <p class="help-block text-muted"><small>{!! trans('landingpages::global.name_help') !!}</small></p>
              <input type="text" class="form-control" id="name" name="name" autocomplete="off" value="<?php echo (isset($page)) ? $page->name : $form->name ; ?>">
          </div>
        </div>
      </div>

      <br>

      <div class="form-group">
        <label for="title">{{ trans('landingpages::global.page_title') }}</label>
          <p class="help-block text-muted"><small>{!! trans('landingpages::global.page_title_help') !!}</small></p>
          <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="description">{{ trans('landingpages::global.page_description') }}</label>
          <p class="help-block text-muted"><small>{!! trans('landingpages::global.page_description_help') !!}</small></p>
          <textarea class="form-control" id="description" name="description" autocomplete="off" rows="4"></textarea>
      </div>


      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
        <button type="button" class="btn btn-primary btn-material ladda-button onClickUpdate" data-style="zoom-in" data-spinner-color="#138dfa"><span class="ladda-label">{{ trans('global.save') }}</span></button>
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

  var $el = $('html', window.parent.document);

  $('#title').val($el.find('title').text());
  $('#description').val($el.find('meta[name=description]').attr('content'));

<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {
    var ladda_button = $('button.onClickUpdate').ladda();
    ladda_button.ladda('start');

    var jqxhr = $.ajax({
<?php if (isset($page)) { ?>
      url: "{{ url('landingpages/editor/seo') }}",
<?php } else { ?>
      url: "{{ url('forms/editor/seo') }}",
<?php } ?>
      data: {name: $('#name').val(), sl: "{{ $sl }}",  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      $el.find('title').text($('#title').val());
      $el.find('meta[name=description]').attr('content', $('#description').val());

      // Update page title
      if (typeof window.parent.parent.$('#generic_title a') !== 'undefined') {
        window.parent.parent.$('#generic_title a').text($('#name').val());
      }

      // Changes detected
      window.parent.lfSetPageIsDirty();
      window.parent.lfCloseModal();
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      ladda_button.ladda('stop');
    });

  });
});
</script>
@endsection