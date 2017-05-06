function lfInitLinks() {

  /*
    Loop through all links, generate semi-unique class
    to reference links for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings link with dropdown to link (Tether).
  */

  $('.-x-link').each(function() {
    var $link = $(this);
    // Attribute settings
    var attachment = $link.attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $link.attr('data-target-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

    var offset = $link.attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px 0';

    var dropdownPosition = $link.attr('data-dropdown-position');
    dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

    var $el = $(xTplLinkButton).clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-x-data-link-' + timestamp;

    $link.addClass(unique_class);
    $link.attr('data-x-el', unique_class);
    $el.attr('data-x-el', unique_class);

    // Set reference to parent block
    $el.attr('data-x-parent-block', $link.parents('.-x-block').attr('data-x-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-x-el-link-edit').addClass('-x-el-link-edit-clone -x-el-inline-button-clone');

    // Add class so dropdown opens on the left side
    if (dropdownPosition == 'left') {
      $el.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
    }

    new Tether({
      element: $el,
      target: $link,
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

  lfParseLinks(true);

  /* 
    Open modal to configure link
  */

  $('body').on('click', '.-x-el-link-edit', function() {
    var el_class = $(this).parents('.-x-el-link-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {
      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').css('cssText', 'display: none !important;');

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/link', el_class);
    }
  });
}

/* 
  Duplicate links and references
*/

function lfDuplicateBlockLinks($new_block) {
  // Loop through all links in new block
  $new_block.find('.-x-link').each(function() {
    var timestamp = new Date().getTime();
    var $new_link = $(this);
    var link_class = $new_link.attr('data-x-el');

    if (typeof link_class !== typeof undefined && link_class !== false) {
      // Attribute settings
      var attachment = $(this).attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $(this).attr('data-target-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

      var offset = $(this).attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px 0';

      // Clone link and replace with new class
      $new_link.removeClass(link_class);
      $new_link.addClass('-x-data-link-' + timestamp);
      $new_link.attr('data-x-el', '-x-data-link-' + timestamp);

      // Settings
      var $new_link_settings = $('.-x-el-link-edit-clone[data-x-el=' + link_class + ']').clone().insertAfter('.-x-el-link-edit-clone[data-x-el=' + link_class + ']');
      $new_link_settings.attr('data-x-el', '-x-data-link-' + timestamp);

      new Tether({
        element: $new_link_settings,
        target: $new_link,
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
  setTimeout(lfParseLinks, 70);
}

/* 
  Loop through link settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseLinks(init) {
  var zIndex = 200;
  
  $('.-x-link').each(function() {
    var link_class = $(this).attr('data-x-el');
    var $link_settings = $('.-x-el-link-edit-clone[data-x-el=' + link_class + ']');


    // Set z-index to prevent overlapping of dropdown menus
    $link_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $link_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $link_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
