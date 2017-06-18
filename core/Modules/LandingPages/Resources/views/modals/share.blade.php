@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.share') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <div class="form-group">
        <label for="url">{{ trans('landingpages::global.url') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{{ trans('landingpages::global.share_url_help') }}">&#xE887;</i></label>
        <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="title">{{ trans('landingpages::global.title') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.share_title_help') }}">&#xE887;</i></label>
        <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="description">{{ trans('landingpages::global.description') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.share_description_help') }}">&#xE887;</i></label>
        <textarea class="form-control" id="description" name="description" autocomplete="off" rows="2"></textarea>
      </div>

      <div class="form-group">
        <label for="hashtags">{{ trans('landingpages::global.hashtags') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.share_hashtags_help') }}">&#xE887;</i></label>
        <input type="text" class="form-control" id="hashtags" name="hashtags" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="media">{{ trans('landingpages::global.media') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.share_media_help') }}">&#xE887;</i></label>
        <input type="text" class="form-control" id="media" name="media" autocomplete="off" value="">
      </div>

      <div class="form-group">
        <label for="via">{{ trans('landingpages::global.twitter_username') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('landingpages::global.share_twitter_username_help') }}">&#xE887;</i></label>
        <input type="text" class="form-control" id="via" name="via" autocomplete="off" value="">
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

  var $el = $('.{{ $el_class }}', window.parent.document);

  var url = $el.attr('data-url');
  $('#url').val(url);

  var title = $el.attr('data-title');
  $('#title').val(title);

  var description = $el.attr('data-description');
  $('#description').val(description);

  var media = $el.attr('data-media');
  $('#media').val(media);

  var hashtags = $el.attr('data-hashtags');
  $('#hashtags').val(hashtags);

  var via = $el.attr('data-via');
  $('#via').val(via);


<?php /* ----------------------------------------------------------------------------
Update settings
*/ ?>

  $('.onClickUpdate').on('click', function() {

<?php if ($el_class != '') { ?>

    $el.attr('data-url', $('#url').val());
    $el.attr('data-title', $('#title').val());
    $el.attr('data-description', $('#description').val());
    $el.attr('data-media', $('#media').val());
    $el.attr('data-hashtags', $('#hashtags').val());
    $el.attr('data-via', $('#via').val());

    // Changes detected
    window.parent.lfSetPageIsDirty();

<?php } ?>

    window.parent.lfCloseModal();

  });
});
</script>
@endsection