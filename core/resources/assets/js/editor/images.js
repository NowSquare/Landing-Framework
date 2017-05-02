$(function() {
  /*
    Loop through all images, generate semi-unique class
    to reference images for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings button with dropdown to image (Tether).
  */

  $('.-lf-img').each(function() {
    // Attribute settings
    var attachment = $(this).attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $(this).attr('data-taget-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

    var offset = $(this).attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

    var $el = $('.-lf-el-img-edit').clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-lf-data-img-' + timestamp;

    $(this).addClass(unique_class);
    $(this).attr('data-lf-el', unique_class);
    $el.attr('data-lf-el', unique_class);

    // Set reference to parent block
    $el.attr('data-lf-parent-block', $(this).parents('.-lf-block').attr('data-lf-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-lf-el-img-edit').addClass('-lf-el-img-edit-clone -lf-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $(this),
      attachment: attachment,
      offset: offset,
      targetAttachment: targetAttachment,
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

  lf_ParseImages(true);
});

/* 
  Duplicate image buttons and references
*/

function lf_DuplicateBlockImages($new_block) {
  // Loop through all images in new block
  $new_block.find('.-lf-img').each(function() {
    var timestamp = new Date().getTime();
    var $new_img = $(this);
    var img_class = $new_img.attr('data-lf-el');

    if (typeof img_class !== typeof undefined && img_class !== false) {
      // Attribute settings
      var attachment = $new_img.attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $new_img.attr('data-taget-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

      var offset = $new_img.attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

      // Clone img and replace with new class
      $new_img.removeClass(img_class);
      $new_img.addClass('-lf-data-img-' + timestamp);
      $new_img.attr('data-lf-el', '-lf-data-img-' + timestamp);

      // Settings
      var $new_img_settings = $('.-lf-el-img-edit-clone[data-lf-el=' + img_class + ']').clone().insertAfter('.-lf-el-img-edit-clone[data-lf-el=' + img_class + ']');
      $new_img_settings.attr('data-lf-el', '-lf-data-img-' + timestamp);

      new Tether({
        element: $new_img_settings,
        target: $new_img,
        attachment: attachment,
        offset: offset,
        targetAttachment: targetAttachment,
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
    }

  });

  // Timeout to make sure dom has changed
  setTimeout(lf_ParseImages, 70);
}

/* 
  Loop through img settings to set attributes
  and fix z-index overlapping. 
*/

function lf_ParseImages(init) {
  var zIndex = 100;
  
  $('.-lf-img').each(function() {
    var img_class = $(this).attr('data-lf-el');
    var $img_settings = $('.-lf-el-img-edit-clone[data-lf-el=' + img_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $img_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $img_settings.find('.-lf-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $img_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}