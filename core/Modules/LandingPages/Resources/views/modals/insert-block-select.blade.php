@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::block.' . $category_name) }} |  <a href="{{ url('landingpages/editor/modal/insert-block?el_class=' . $el_class . '&position=' . $position) }}">{{ trans('global.back') }}</a></h1>
  </div>

  <div class="row">
<?php
$i = 1;
foreach($blocks as $block) {
?>
    <div class="col-xs-12 col-sm-4 col-lg-3">

      <div class="portlet shadow-box">
        <div>
          <a href="javascript:void(0);" class="preview-container onClickInsert" data-category="{{ $category }}" data-block="{{ $block['file'] }}" id="container{{ $i }}">
            <iframe src="{{ $block['preview'] }}" id="frame{{ $i }}" class="preview_frame" frameborder="0" seamless></iframe>
          </a>
        </div>
      </div>

    </div>
<?php
  $i++;
}
?>
    <div class="col-xs-10 col-sm-6">
      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script') 
<style type="text/css">
.preview-container {
  overflow: hidden;
  display: block;
  width:100%;
  height: 120px;
}
.loader.loader-xs {
  margin: -6px auto 0;
}
.preview_frame {
  pointer-events: none;
  width:400%;
  -ms-zoom: 0.25;
  -moz-transform: scale(0.25);
  -moz-transform-origin: 0 0;
  -o-transform: scale(0.25);
  -o-transform-origin: 0 0;
  -webkit-transform: scale(0.25);
  -webkit-transform-origin: 0 0;
}
</style>
<script>
$(function() {

blockUI('.preview-container');

$(window).resize(resizeEditFrame);

function resizeEditFrame() {
  $('.preview_frame').each(function() {
    var frame_height = parseInt($(this).contents().find('html').height());
    var frame_width = parseInt($(this).contents().find('html').width());

    $(this).height(frame_height);

    $(this).parent().height(frame_height / 4);
    //$(this).parent().width(frame_width / 4);
    $(this).parent().width('100%');
  });
}

<?php
$i = 1;
foreach($blocks as $block) {
?>
$('#frame{{ $i }}').load(function() {
  resizeEditFrame();
  unblockUI('#container{{ $i }}');
});
<?php
  $i++;
}
?>

<?php /* ----------------------------------------------------------------------------
Insert block
*/ ?>

  $('.onClickInsert').on('click', function() {
<?php if ($el_class != '') { ?>

    var html = $(this).find('.preview_frame')[0].contentWindow.document.body.innerHTML;
    var $el = $('.{{ $el_class }}', window.parent.document);

<?php if ($position == 'above') { ?>
    var $new_block = $(html).insertBefore($el);

    // Make new block editable
    window.parent.lfMakeNewBlockEditable($new_block, '{{ $el_class }}', 'after');

<?php } else { ?>
    var $new_block = $(html).insertAfter($el);

    // Make new block editable
    window.parent.lfMakeNewBlockEditable($new_block, '{{ $el_class }}', 'above');
<?php } ?>

    // Changes detected
    window.parent.lfSetPageIsDirty();
<?php } ?>

    window.parent.lfCloseModal();
  });

  // Focus window and bind escape to close
  $(window).focus();

  $(document).keyup(function(e) {
    if(e.keyCode === 27) {
      window.parent.lfCloseModal();
    }
  });
});
</script>
@endsection