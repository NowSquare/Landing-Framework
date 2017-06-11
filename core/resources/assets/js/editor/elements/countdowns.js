/*
  Generate semi-unique class
  to reference countdowns for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link dropdown to list (Tether).
*/

function lfInitCountdown($countdown, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-countdown-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplCountdownButton).clone().appendTo('body');
  } else {
    var countdown_class = unique_class;

    // New unique class
    var unique_class = '-x-data-countdown-' + timestamp;

    // Clone countdown and replace with new class
    $countdown.removeClass(countdown_class);
    $countdown.addClass(unique_class);
    $countdown.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-countdown-edit-clone[data-x-el=' + countdown_class + ']').clone().insertAfter('.-x-el-countdown-edit-clone[data-x-el=' + countdown_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $countdown.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'bottom left';

  var targetAttachment = $countdown.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $countdown.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

  var dropdownPosition = $countdown.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $countdown.addClass(unique_class);
  $countdown.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $countdown.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-countdown-edit').addClass('-x-el-countdown-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-countdown-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-countdown-visible .-x-el-checkmark').addClass('-x-checked');
  }

  new Tether({
    element: $edit_button,
    target: $countdown,
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

  // Check position on page
  var offset_bottom = (parseInt($(document).outerHeight(true)) - parseInt($edit_button.position().top));
  var offset_right = (parseInt($(document).outerWidth(true)) - parseInt($edit_button.position().left));
  var $dropdown = $edit_button.find('.-x-el-dropdown');
  $dropdown.removeClass('-x-el-dropdown-up -x-el-dropdown-left');

  if (offset_bottom < 250) {
    $dropdown.addClass('-x-el-dropdown-up');
  }

  if (offset_right < 250) {
    $dropdown.addClass('-x-el-dropdown-left');
  }
}

function lfInitCountdowns() {

  /*
    Loop through all countdowns, generate semi-unique class
    to reference countdowns for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link dropdown to list (Tether).
  */

  $('.-x-countdown').each(function() {
    var $countdown = $(this);
    lfInitCountdown($countdown);
  });

  lfParseCountdowns(true);

  /* 
    Open modal to configure countdown
  */

  $('body').on('click', '.-x-el-countdown-edit', function() {
    var el_class = $(this).parents('.-x-el-countdown-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/countdown', el_class);
    }
  });

  /* 
    Toggle countdown visibility
  */

  $('body').on('click', '.-x-el-countdown-visible', function() {
    var el_class = $(this).parents('.-x-el-countdown-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Toggle .invisible class
      var $el = $('.' + el_class);
      $el.toggleClass('invisible');
      $(this).find('.-x-el-checkmark').toggleClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });
}

/* 
  Duplicate countdowns and references
*/

function lfDuplicateBlockCountdowns($new_block) {

  // Loop through all countdowns in new block
  $new_block.find('.-x-countdown').each(function() {
    var timestamp = new Date().getTime();
    var $new_countdown = $(this);
    var countdown_class = $new_countdown.attr('data-x-el');

    lfInitCountdown($new_countdown, countdown_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseCountdowns, 70);
}

/* 
  Loop through countdown settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseCountdowns(init) {
  var zIndex = 200;
  
  $('.-x-countdown').each(function() {
    var countdown_class = $(this).attr('data-x-el');
    var $countdown_settings = $('.-x-el-countdown-edit-clone[data-x-el=' + countdown_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $countdown_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $countdown_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $countdown_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}