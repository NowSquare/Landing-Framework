function lfInitImages() {
  /*
    Loop through all images, generate semi-unique class
    to reference images for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings button with dropdown to image (Tether).
  */

  $('.-x-img').each(function() {
    var $img = $(this);
    // Attribute settings
    var attachment = $img.attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $img.attr('data-target-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

    var offset = $img.attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

    var dropdownPosition = $list.attr('data-dropdown-position');
    dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

    var $el = $(xTplImgButton).clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-img-' + timestamp;

    $img.addClass(unique_class);
    $img.attr('data-x-el', unique_class);
    $el.attr('data-x-el', unique_class);

    // Set reference to parent block
    $el.attr('data-x-parent-block', $img.parents('.-x-block').attr('data-x-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-x-el-img-edit').addClass('-x-el-img-edit-clone -x-el-inline-button-clone');

    // Add class so dropdown opens on the left side
    if (dropdownPosition == 'left') {
      $el.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
    }

    new Tether({
      element: $el,
      target: $img,
      attachment: attachment,
      offset: offset,
      targetAttachment: targetAttachment,
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

  lfParseImages(true);

  /* 
    Open modal to configure image
  */

  $('body').on('click', '.-x-el-img-update', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {
      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').css('cssText', 'display: none !important;');

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/image', el_class);
    }
  });
}

/* 
  Duplicate image buttons and references
*/

function lfDuplicateBlockImages($new_block) {
  // Loop through all images in new block
  $new_block.find('.-x-img').each(function() {
    var timestamp = new Date().getTime();
    var $new_img = $(this);
    var img_class = $new_img.attr('data-x-el');

    if (typeof img_class !== typeof undefined && img_class !== false) {
      // Attribute settings
      var attachment = $new_img.attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $new_img.attr('data-target-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

      var offset = $new_img.attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

      // Clone img and replace with new class
      $new_img.removeClass(img_class);
      $new_img.addClass('-x-data-img-' + timestamp);
      $new_img.attr('data-x-el', '-x-data-img-' + timestamp);

      // Settings
      var $new_img_settings = $('.-x-el-img-edit-clone[data-x-el=' + img_class + ']').clone().insertAfter('.-x-el-img-edit-clone[data-x-el=' + img_class + ']');
      $new_img_settings.attr('data-x-el', '-x-data-img-' + timestamp);

      new Tether({
        element: $new_img_settings,
        target: $new_img,
        attachment: attachment,
        offset: offset,
        targetAttachment: targetAttachment,
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
    }

  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseImages, 70);
}

/* 
  Loop through img settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseImages(init) {
  var zIndex = 100;
  
  $('.-x-img').each(function() {
    var img_class = $(this).attr('data-x-el');
    var $img_settings = $('.-x-el-img-edit-clone[data-x-el=' + img_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $img_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $img_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $img_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}