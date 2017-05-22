@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::block.' . $category_name) }} |  <a href="{{ url('landingpages/editor/modal/insert-block?el_class=' . $el_class . '&position=' . $position) }}">{{ trans('global.back') }}</a></h1>
  </div>

  <div class="row grid">
    <div class="grid-sizer col-xs-4" style="display:none"></div>
<?php
$i = 1;
foreach($blocks as $block) {
?>
    <div class="grid-item col-xs-12 col-sm-4 col-lg-3">

      <div class="grid-item-content portlet shadow-box">
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
  display: block;
  width:100%;
  height: 120px;
}
.loader.loader-xs {
  margin: -6px auto 0;
}
.preview_frame {
  pointer-events: none;
  width: 400%;
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
  $('#frame{{ $i }}').on('load', function() {
    resizeEditFrame();
    unblockUI('#container{{ $i }}');
<?php if ($i == count($blocks)) { ?>
  var $grid = $('.grid').masonry({
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    transitionDuration: '0.2s'
  });
<?php } ?>
  });
<?php
  $i++;
}
?>

<?php /* ----------------------------------------------------------------------------
Insert block
*/ ?>

  $('.onClickInsert').on('click', function() {
    var html = $(this).find('.preview_frame')[0].contentWindow.document.body.innerHTML;

<?php if ($el_class != '') { ?>
    var $el = $('.{{ $el_class }}', window.parent.document);

<?php if ($position == 'above') { ?>
    var $new_block = $(html).insertBefore($el);

    // Make new block editable
    window.parent.lfMakeNewBlockEditable($new_block, '{{ $el_class }}', 'after');

    // Scroll to element
    window.parent.$('html, body').animate({
      scrollTop: parseInt($el.offset().top) - parseInt($new_block[0].scrollHeight)
    }, 400);

<?php } else { ?>
    var $new_block = $(html).insertAfter($el);

    // Make new block editable
    window.parent.lfMakeNewBlockEditable($new_block, '{{ $el_class }}', 'above');

    // Scroll to element
    window.parent.$('html, body').animate({
      scrollTop: parseInt($el.offset().top) + parseInt($el[0].scrollHeight)
    }, 400);

<?php } ?>

<?php } else { /* ----------------------------------------------------------------------------
Insert block at bottom of page
*/
?>
    // Check if there are blocks
    var last_block_class = $('.-x-block[data-x-el]', window.parent.document).last().attr('data-x-el');

    if (typeof last_block_class !== 'undefined' && last_block_class != null) {
      var $el = $('.' + last_block_class, window.parent.document);
      var $new_block = $(html).insertAfter($el);

      // Make new block editable
      window.parent.lfMakeNewBlockEditable($new_block, last_block_class, 'above');
    } else {
      // There are no blocks
      var $el = $('body', window.parent.document);
      var $new_block = $(html).prependTo($el);

      // Make new block editable
      window.parent.lfMakeNewBlockEditable($new_block);
    }

    // Scroll to element
    window.parent.$('html, body').animate({
      scrollTop: parseInt($el.offset().top) + parseInt($el[0].scrollHeight)
    }, 400);

<?php } ?>

    // Changes detected
    window.parent.lfSetPageIsDirty();

    window.parent.lfCloseModal();
  });
});
</script>
@endsection