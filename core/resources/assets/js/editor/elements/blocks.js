// Elements with padding config
var lf_padding_elements = ['content', 'photos', 'footer', 'cards'];

function lfInitBlocks() {

  /*
    Loop through all blocks, generate semi-unique class
    to reference block for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings button with dropdown to block (Tether).
  */

  $('.-x-block').each(function() {
    var $block = $(this);
    var $button = $(xTplBlockButton).clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-block-' + timestamp;

    $block.addClass(unique_class);
    $block.attr('data-x-el', unique_class);
    $button.attr('data-x-el', unique_class);

    // Replace class so it won't be cloned in next loop
    $button.removeClass('-x-el-block-edit').addClass('-x-el-block-edit-clone -x-el-inline-button-clone');

    // Check background image and/or color is available
    if ($block.find('.-x-block-bg-img').length || $block.find('.-x-block-bg-color').length || $block.find('.-x-block-bg-gradient').length) {
      $button.find('.-x-el-block-background').removeClass('-x-el-disabled');
    } else {
      $button.find('.-x-el-block-background').addClass('-x-el-disabled');
    }

    new Tether({
      element: $button,
      target: $block,
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

    // Check position on page
    var offset_bottom = (parseInt($(document).outerHeight(true)) - parseInt($button.position().top));
    var $dropdown = $button.find('.-x-el-dropdown');
    $dropdown.removeClass('-x-el-dropdown-up'); 

    if (offset_bottom < 250) {
      $dropdown.addClass('-x-el-dropdown-up');
    }

  });

  lfParseBlocks(true);

  /* 
    Open modal to configure background options
  */

  $('body').on('click', '.-x-el-block-background', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_class !== typeof undefined && block_class !== false) {
      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $block = $('.' + block_class);
      var bg_img = ($block.find('.-x-block-bg-img').length) ? 1 : 0;
      var bg_color = ($block.find('.-x-block-bg-color').length) ? 1 : 0;
      var bg_gradient = ($block.find('.-x-block-bg-gradient').length) ? 1 : 0;

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/background?bg_img=' + bg_img + '&bg_color=' + bg_color + '&bg_gradient=' + bg_gradient, block_class);
    }
  });

  /* 
    Open modal to insert block
  */

  $('body').on('click', '.-x-el-block-insert-above, .-x-el-block-insert-below', function() {
    var position = $(this).hasClass('-x-el-block-insert-above') ? 'above' : 'below';
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_class !== typeof undefined && block_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/insert-block?position=' + position + '', block_class);
    }
  });

  /* 
    Move block one position up
  */

  $('body').on('click', '.-x-el-block-move-up', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');
    var block_prev = $('.' + block_class).attr('data-x-prev');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_prev !== typeof undefined && block_prev !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lfSwapElements($('.' + block_prev)[0], $('.' + block_class)[0]);

      // Changes detected
      lfSetPageIsDirty();

      // Timeout to make sure dom has changed
      setTimeout(lfParseBlocks, 70);
    }
  });

  /* 
    Move block one position down
  */

  $('body').on('click', '.-x-el-block-move-down', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');
    var block_next = $('.' + block_class).attr('data-x-next');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_next !== typeof undefined && block_next !== false && typeof block_class !== typeof undefined && block_class !== false) {
      lfSwapElements($('.' + block_class)[0], $('.' + block_next)[0]);

      // Changes detected
      lfSetPageIsDirty();

      // Timeout to make sure dom has changed
      setTimeout(lfParseBlocks, 70);
    }
  });

  /* 
    Delete block
  */

  $('body').on('click', '.-x-el-block-edit-delete', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_class !== typeof undefined && block_class !== false) {
      if (confirm(_lang['confirm_delete_block'])) {
        $('.-x-el-block-edit-clone[data-x-el=' + block_class + ']').remove();
        $('.' + block_class).remove();

        // Delete other elements
        $('[data-x-parent-block=' + block_class + ']').each(function() {
          $(this).remove();
        });

        // Changes detected
        lfSetPageIsDirty();

        // Timeout to make sure dom has changed
        setTimeout(lfParseBlocks, 70);
      }
    }
  });

  /* 
    Duplicate block
  */

  $('body').on('click', '.-x-el-block-edit-duplicate', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_class !== typeof undefined && block_class !== false) {
 
      // Clone block and remove class
      var $new_block = $('.' + block_class).clone().insertAfter('.' + block_class);
      $new_block.removeClass(block_class);

      // Make new block editable
      lfMakeNewBlockEditable($new_block, block_class, 'after', block_class);
    }
  });

  /* 
    Change padding
  */

  $('body').on('click', '.-x-el-block-padding-select', function() {
    var block_class = $(this).parents('.-x-el-block-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof block_class !== typeof undefined && block_class !== false) {

      var selected_padding = $(this).attr('data-x-padding');

      // Unselect all
      $(this).parents('ul').find('.-x-el-block-padding-select .-x-el-checkmark').removeClass('-x-checked');

      // Select padding
      $(this).parents('ul').find('.-x-el-block-padding-select[data-x-padding=' + selected_padding + '] .-x-el-checkmark').addClass('-x-checked');

      // Find element type
      for (var i = 0, len = lf_padding_elements.length; i < len; i++) {
        var padding_element = lf_padding_elements[i];
        var $el = $('.' + block_class);
        
        if ($el.find('.' + padding_element).length) {
          var $padding_element = $el.find('.' + padding_element);
          // Remove all padding classes
          $padding_element.removeClass(padding_element + '-padding-l ' + padding_element + '-padding-xl ' + padding_element + '-padding-xxl');

          // Add padding class
          if (selected_padding != 'none') {
            $padding_element.addClass(padding_element + '-padding-' + selected_padding);
          }
        }
      }

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });

}

/* 
  Make block and elements inside editable
*/

function lfMakeNewBlockEditable($new_block, previous_el, position, block_class) {
  var timestamp = new Date().getTime();

  $new_block.addClass('-x-data-block-' + timestamp);
  $new_block.attr('data-x-el', '-x-data-block-' + timestamp);

  // Settings
  if (typeof block_class !== 'undefined' && block_class != null) {
    var $new_block_settings = $('.-x-el-block-edit-clone[data-x-el=' + block_class + ']').clone().insertAfter('.-x-el-block-edit-clone[data-x-el=' + block_class + ']');
  } else {
    if (typeof previous_el !== 'undefined') {
      if (position == 'after') {
        var $new_block_settings = $(xTplBlockButton).clone().insertAfter('.-x-el-block-edit-clone[data-x-el=' + previous_el + ']');
      } else {
        var $new_block_settings = $(xTplBlockButton).clone().insertBefore('.-x-el-block-edit-clone[data-x-el=' + previous_el + ']');
      }
    } else {
      var $new_block_settings = $(xTplBlockButton).clone().prependTo('body');
    }

    // Replace class
    $new_block_settings.removeClass('-x-el-block-edit').addClass('-x-el-block-edit-clone -x-el-inline-button-clone');
  }

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

  // Changes detected
  lfSetPageIsDirty();

  // Timeout to make sure dom has changed
  setTimeout(lfParseBlocks, 70);

  // Duplicate other elements
  lfDuplicateBlockImages($new_block);
  lfDuplicateBlockVideos($new_block);
  lfDuplicateBlockIcons($new_block);
  lfDuplicateBlockLinks($new_block);
  lfDuplicateBlockLists($new_block);
  lfDuplicateBlockCountdowns($new_block);
  lfDuplicateBlockForms($new_block);
  lfDuplicateBlockText($new_block);

  if (typeof lfDuplicateBlockHook === 'function') {
    lfDuplicateBlockHook($new_block);
  }
}

/* 
  Loop through block settings to set attributes
  and fix z-index overlapping. This function is
  called initially after settings are cloned, and
  later after layout changes like moving blocks.
*/

function lfParseBlocks(init) {
  var zIndex = 200;
  
  $('.-x-block').each(function() {
    var $block = $(this);
    var block_class = $block.attr('data-x-el');
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

    // Check padding
    for (var i = 0, len = lf_padding_elements.length; i < len; i++) {
      var padding_element = lf_padding_elements[i];

      if ($block.find('.' + padding_element).length) {
        $block_padding = $block.find('.' + padding_element);
        $block_settings.find('.-x-el-block-padding').removeClass('-x-el-disabled');

        if ($block_padding.hasClass(padding_element + '-padding-l')) {
          $block_settings.find('.-x-el-block-padding-select[data-x-padding=l] .-x-el-checkmark').addClass('-x-checked');
        } else if ($block_padding.hasClass('photos-padding-xl')) {
          $block_settings.find('.-x-el-block-padding-select[data-x-padding=xl] .-x-el-checkmark').addClass('-x-checked');
        } else if ($block_padding.hasClass('photos-padding-xxl')) {
          $block_settings.find('.-x-el-block-padding-select[data-x-padding=xxl] .-x-el-checkmark').addClass('-x-checked');
        } else {
          $block_settings.find('.-x-el-block-padding-select[data-x-padding=none] .-x-el-checkmark').addClass('-x-checked');
        }
      }
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
