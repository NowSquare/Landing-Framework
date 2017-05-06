function lfInitLists() {

  /*
    Loop through all lists, generate semi-unique class
    to reference lists for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    list settings list with dropdown to list (Tether).
  */

  $('.-x-list').each(function() {
    var $list = $(this);
    // Attribute settings
    var attachment = $list.attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $list.attr('data-target-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

    var offset = $list.attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

    var $el = $(xTplListButton).clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-list-' + timestamp;

    $list.addClass(unique_class);
    $list.attr('data-x-el', unique_class);
    $el.attr('data-x-el', unique_class);

    // Set reference to parent block
    $el.attr('data-x-parent-block', $list.parents('.-x-block').attr('data-x-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-x-el-list-edit').addClass('-x-el-list-edit-clone -x-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $list,
      attachment: attachment,
      offset: offset,
      targetAttachment: targetAttachment,
      classPrefix: '-x-data',
      constraints: [{
        to: 'window',
        attachment: 'together'
      }],
      optimizations: {
        moveElement: true,
        gpu: true
      }
    });
  });

  lfParseLists(true);

  /* 
    Open modal to configure list
  */

  $('body').on('click', '.-x-el-list-edit', function() {
    var el_class = $(this).parents('.-x-el-list-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {
      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').css('cssText', 'display: none !important;');

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/list', el_class);
    }
  });
}

/* 
  Duplicate lists and references
*/

function lfDuplicateBlockLists($new_block) {
  // Loop through all lists in new block
  $new_block.find('.-x-list').each(function() {
    var timestamp = new Date().getTime();
    var $new_list = $(this);
    var list_class = $new_list.attr('data-x-el');

    if (typeof list_class !== typeof undefined && list_class !== false) {
      // Attribute settings
      var attachment = $(this).attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $(this).attr('data-target-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

      var offset = $(this).attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

      // Clone btn and replace with new class
      $new_list.removeClass(list_class);
      $new_list.addClass('-x-data-list-' + timestamp);
      $new_list.attr('data-x-el', '-x-data-list-' + timestamp);

      // Settings
      var $new_list_settings = $('.-x-el-list-edit-clone[data-x-el=' + list_class + ']').clone().insertAfter('.-x-el-list-edit-clone[data-x-el=' + list_class + ']');
      $new_list_settings.attr('data-x-el', '-x-data-list-' + timestamp);

      new Tether({
        element: $new_list_settings,
        target: $new_list,
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
  setTimeout(lfParseLists, 70);
}

/* 
  Loop through list settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseLists(init) {
  var zIndex = 200;
  
  $('.-x-list').each(function() {
    var list_class = $(this).attr('data-x-el');
    var $list_settings = $('.-x-el-list-edit-clone[data-x-el=' + list_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $list_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $list_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $list_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}