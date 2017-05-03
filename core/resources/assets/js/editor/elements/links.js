$(function() {

  /*
    Loop through all links, generate semi-unique class
    to reference links for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings link with dropdown to link (Tether).
  */

  $('.-x-link').each(function() {
    // Attribute settings
    var attachment = $(this).attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $(this).attr('data-target-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

    var offset = $(this).attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px 0';

    var $el = $('.-x-el-link-edit').clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-link-' + timestamp;

    $(this).addClass(unique_class);
    $(this).attr('data-x-el', unique_class);
    $el.attr('data-x-el', unique_class);

    // Set reference to parent block
    $el.attr('data-x-parent-block', $(this).parents('.-x-block').attr('data-x-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-x-el-link-edit').addClass('-x-el-link-edit-clone -x-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $(this),
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

  lf_ParseLinks(true);
});

/* 
  Duplicate links and references
*/

function lf_DuplicateBlockLinks($new_block) {
  // Loop through all links in new block
  $new_block.find('.-x-link').each(function() {
    var timestamp = new Date().getTime();
    var $new_btn = $(this);
    var btn_class = $new_btn.attr('data-x-el');

    if (typeof btn_class !== typeof undefined && btn_class !== false) {
      // Attribute settings
      var attachment = $new_btn.attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $new_btn.attr('data-target-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

      var offset = $new_btn.attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

      // Clone btn and replace with new class
      $new_btn.removeClass(btn_class);
      $new_btn.addClass('-x-data-link-' + timestamp);
      $new_btn.attr('data-x-el', '-x-data-link-' + timestamp);

      // Settings
      var $new_btn_settings = $('.-x-el-link-edit-clone[data-x-el=' + btn_class + ']').clone().insertAfter('.-x-el-link-edit-clone[data-x-el=' + btn_class + ']');
      $new_btn_settings.attr('data-x-el', '-x-data-link-' + timestamp);

      new Tether({
        element: $new_btn_settings,
        target: $new_btn,
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
  setTimeout(lf_ParseLinks, 70);
}

/* 
  Loop through link settings to set attributes
  and fix z-index overlapping. 
*/

function lf_ParseLinks(init) {
  var zIndex = 200;
  
  $('.-x-link').each(function() {
    var btn_class = $(this).attr('data-x-el');
    var $btn_settings = $('.-x-el-link-edit-clone[data-x-el=' + btn_class + ']');


    // Set z-index to prevent overlapping of dropdown menus
    $btn_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $btn_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $btn_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
