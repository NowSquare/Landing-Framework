$(function() {
  /*
    Loop through all blocks, generate semi-unique class
    to reference block for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings button with dropdown to block (Tether).
  */

  $('.-x-block').each(function() {
    var $el = $('.-x-el-block-edit').clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-block-' + timestamp;

    $(this).addClass(unique_class);
    $(this).attr('data-x-el', unique_class);
    $el.attr('data-x-el', unique_class);

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-x-el-block-edit').addClass('-x-el-block-edit-clone -x-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $(this),
      attachment: 'top left',
      offset: '-5px -5px',
      targetAttachment: 'top left',
      classPrefix: '-x-data',
      constraints: [{
        to: 'scrollParent',
        attachment: 'together'
      }],
      optimizations: {
        moveElement: true,
        gpu: true
      }
    });
  });

  lf_ParseBlocks(true);

  /* 
    Block settings UI. Show button on hover,
    open menu on click, etc.
  */
/*
  $('body').on('mouseenter', '.-x-block', function() {
    var block_class = $(this).attr('data-x-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      var $block_settings = $('.-x-block-edit-clone[data-x-el=' + block_class + ']');
      $block_settings.css('cssText', 'display: block !important;');

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  $('body').on('mouseleave', '.-x-block', function() {
    var isHovered = $(':hover').filter($('.-x-block-edit-clone'));
    if (isHovered.length == 0) {
      var block_class = $(this).attr('data-x-el');

      if (typeof block_class !== typeof undefined && block_class !== false) {
        var $block_settings = $('.-x-block-edit-clone[data-x-el=' + block_class + ']');
        $block_settings.css('cssText', 'display: none !important;');

        // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
        Tether.position();
      }
    }
  });
*/


  /* 
    Move block one position up
  */

  $('body').on('click', '.-x-el-block-move-up', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');
    var block_prev = $('.' + block_class).attr('data-x-prev');

    if (typeof block_prev !== typeof undefined && block_prev !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lf_SwapElements($('.' + block_prev)[0], $('.' + block_class)[0]);

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Move block one position down
  */

  $('body').on('click', '.-x-el-block-move-down', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');
    var block_next = $('.' + block_class).attr('data-x-next');

    if (typeof block_next !== typeof undefined && block_next !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lf_SwapElements($('.' + block_class)[0], $('.' + block_next)[0]);

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Delete block
  */

  $('body').on('click', '.-x-el-block-edit-delete', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      $('.-x-el-block-edit-clone[data-x-el=' + block_class + ']').remove();
      $('.' + block_class).remove();

      // Delete other elements
      $('[data-x-parent-block=' + block_class + ']').each(function() {
        $(this).remove();
      });

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Duplicate block
  */

  $('body').on('click', '.-x-el-block-edit-duplicate', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      var timestamp = new Date().getTime();
 
      // Clone block and replace with new class
      var $new_block = $('.' + block_class).clone().insertAfter('.' + block_class);
      $new_block.removeClass(block_class);
      $new_block.addClass('-x-data-block-' + timestamp);
      $new_block.attr('data-x-el', '-x-data-block-' + timestamp);

      // Settings
      var $new_block_settings = $('.-x-el-block-edit-clone[data-x-el=' + block_class + ']').clone().insertAfter('.-x-el-block-edit-clone[data-x-el=' + block_class + ']');
      $new_block_settings.attr('data-x-el', '-x-data-block-' + timestamp);

      new Tether({
        element: $new_block_settings,
        target: $new_block,
        attachment: 'top left',
        offset: '-5px -5px',
        targetAttachment: 'top left',
        classPrefix: '-x-data',
        constraints: [{
          to: 'scrollParent',
          attachment: 'together'
        }],
        optimizations: {
          moveElement: true,
          gpu: true
        }
      });

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);

      // Duplicate other elements
      lf_DuplicateBlockImages($new_block);
      lf_DuplicateBlockLinks($new_block);
    }
  });

});

/* 
  Loop through block settings to set attributes
  and fix z-index overlapping. This function is
  called initially after settings are cloned, and
  later after layout changes like moving blocks.
*/

function lf_ParseBlocks(init) {
  var zIndex = 200;
  
  $('.-x-block').each(function() {
    var block_class = $(this).attr('data-x-el');
    var $block_settings = $('.-x-el-block-edit-clone[data-x-el=' + block_class + ']');

    // Check if block is first
    var prev = $('.' + block_class).prevAll('.-x-block').first();
    var first = ! prev.length;

    if (first) {
      $block_settings.find('.-x-el-block-move-up').addClass('-x-el-disabled');
      $('.' + block_class).attr('data-x-prev', null);
    } else {
      $block_settings.find('.-x-el-block-move-up').removeClass('-x-el-disabled');
      $('.' + block_class).attr('data-x-prev', prev.attr('data-x-el'));
    }

    // Check if block is last
    var next = $('.' + block_class).nextAll('.-x-block').first();
    var last = ! next.length;

    if (last) {
      $block_settings.find('.-x-el-block-move-down').addClass('-x-el-disabled');
      $('.' + block_class).attr('data-x-next', null);
    } else {
      $block_settings.find('.-x-el-block-move-down').removeClass('-x-el-disabled');
      $('.' + block_class).attr('data-x-next', next.attr('data-x-el'));
    }

    // Set z-index to prevent overlapping of dropdown menus
    $block_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $block_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $block_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
