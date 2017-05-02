$(function() {
  /*
    Loop through all blocks, generate semi-unique class
    to reference block for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings button with dropdown to block (Tether).
  */

  $('.-lf-block').each(function() {
    var $el = $('.-lf-el-block-edit').clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-lf-data-block-' + timestamp;

    $(this).addClass(unique_class);
    $(this).attr('data-lf-el', unique_class);
    $el.attr('data-lf-el', unique_class);

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-lf-el-block-edit').addClass('-lf-el-block-edit-clone -lf-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $(this),
      attachment: 'top left',
      offset: '-5px -5px',
      targetAttachment: 'top left',
      classPrefix: '-lf-data',
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
  $('body').on('mouseenter', '.-lf-block', function() {
    var block_class = $(this).attr('data-lf-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      var $block_settings = $('.-lf-block-edit-clone[data-lf-el=' + block_class + ']');
      $block_settings.css('cssText', 'display: block !important;');

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  $('body').on('mouseleave', '.-lf-block', function() {
    var isHovered = $(':hover').filter($('.-lf-block-edit-clone'));
    if (isHovered.length == 0) {
      var block_class = $(this).attr('data-lf-el');

      if (typeof block_class !== typeof undefined && block_class !== false) {
        var $block_settings = $('.-lf-block-edit-clone[data-lf-el=' + block_class + ']');
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

  $('body').on('click', '.-lf-el-block-move-up', function() {
    var block_class = $(this).parents('.-lf-el-block-edit-clone').attr('data-lf-el');
    var block_prev = $('.' + block_class).attr('data-lf-prev');

    if (typeof block_prev !== typeof undefined && block_prev !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lf_SwapElements($('.' + block_prev)[0], $('.' + block_class)[0]);

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Move block one position down
  */

  $('body').on('click', '.-lf-el-block-move-down', function() {
    var block_class = $(this).parents('.-lf-el-block-edit-clone').attr('data-lf-el');
    var block_next = $('.' + block_class).attr('data-lf-next');

    if (typeof block_next !== typeof undefined && block_next !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lf_SwapElements($('.' + block_class)[0], $('.' + block_next)[0]);

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Delete block
  */

  $('body').on('click', '.-lf-el-block-edit-delete', function() {
    var block_class = $(this).parents('.-lf-el-block-edit-clone').attr('data-lf-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      $('.-lf-el-block-edit-clone[data-lf-el=' + block_class + ']').remove();
      $('.' + block_class).remove();

      // Delete other elements
      $('[data-lf-parent-block=' + block_class + ']').each(function() {
        $(this).remove();
      });

      // Timeout to make sure dom has changed
      setTimeout(lf_ParseBlocks, 70);
    }
  });

  /* 
    Duplicate block
  */

  $('body').on('click', '.-lf-el-block-edit-duplicate', function() {
    var block_class = $(this).parents('.-lf-el-block-edit-clone').attr('data-lf-el');

    if (typeof block_class !== typeof undefined && block_class !== false) {
      var timestamp = new Date().getTime();
 
      // Clone block and replace with new class
      var $new_block = $('.' + block_class).clone().insertAfter('.' + block_class);
      $new_block.removeClass(block_class);
      $new_block.addClass('-lf-data-block-' + timestamp);
      $new_block.attr('data-lf-el', '-lf-data-block-' + timestamp);

      // Settings
      var $new_block_settings = $('.-lf-el-block-edit-clone[data-lf-el=' + block_class + ']').clone().insertAfter('.-lf-el-block-edit-clone[data-lf-el=' + block_class + ']');
      $new_block_settings.attr('data-lf-el', '-lf-data-block-' + timestamp);

      new Tether({
        element: $new_block_settings,
        target: $new_block,
        attachment: 'top left',
        offset: '-5px -5px',
        targetAttachment: 'top left',
        classPrefix: '-lf-data',
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
  
  $('.-lf-block').each(function() {
    var block_class = $(this).attr('data-lf-el');
    var $block_settings = $('.-lf-el-block-edit-clone[data-lf-el=' + block_class + ']');

    // Check if block is first
    var prev = $('.' + block_class).prevAll('.-lf-block').first();
    var first = ! prev.length;

    if (first) {
      $block_settings.find('.-lf-el-block-move-up').addClass('-lf-el-disabled');
      $('.' + block_class).attr('data-lf-prev', null);
    } else {
      $block_settings.find('.-lf-el-block-move-up').removeClass('-lf-el-disabled');
      $('.' + block_class).attr('data-lf-prev', prev.attr('data-lf-el'));
    }

    // Check if block is last
    var next = $('.' + block_class).nextAll('.-lf-block').first();
    var last = ! next.length;

    if (last) {
      $block_settings.find('.-lf-el-block-move-down').addClass('-lf-el-disabled');
      $('.' + block_class).attr('data-lf-next', null);
    } else {
      $block_settings.find('.-lf-el-block-move-down').removeClass('-lf-el-disabled');
      $('.' + block_class).attr('data-lf-next', next.attr('data-lf-el'));
    }

    // Set z-index to prevent overlapping of dropdown menus
    $block_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $block_settings.find('.-lf-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $block_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
