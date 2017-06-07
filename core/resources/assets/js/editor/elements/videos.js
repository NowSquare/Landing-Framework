function lfInitVideo($video, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-video-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplVideoButton).clone().appendTo('body');
  } else {
    var video_class = unique_class;

    // New unique class
    var unique_class = '-x-data-video-' + timestamp;

    // Clone video and replace with new class
    $video.removeClass(video_class);
    $video.addClass(unique_class);
    $video.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-video-edit-clone[data-x-el=' + video_class + ']').clone().insertAfter('.-x-el-video-edit-clone[data-x-el=' + video_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $video.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'bottom left';

  var targetAttachment = $video.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $video.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '5px 0px';

  var dropdownPosition = $video.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $video.addClass(unique_class);
  $video.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $video.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-video-edit').addClass('-x-el-video-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-video-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-video-visible .-x-el-checkmark').addClass('-x-checked');
  }

  if ($video.hasClass('embed-responsive-21by9')) {
    $edit_button.find('.-x-el-video-ratio-select[data-x-ratio=21by9] .-x-el-checkmark').addClass('-x-checked');
  } else if ($video.hasClass('embed-responsive-16by9')) {
    $edit_button.find('.-x-el-video-ratio-select[data-x-ratio=16by9] .-x-el-checkmark').addClass('-x-checked');
  } else if ($video.hasClass('embed-responsive-4by3')) {
    $edit_button.find('.-x-el-video-ratio-select[data-x-ratio=4by3] .-x-el-checkmark').addClass('-x-checked');
  } else if ($video.hasClass('embed-responsive-1by1')) {
    $edit_button.find('.-x-el-video-ratio-select[data-x-ratio=1by1] .-x-el-checkmark').addClass('-x-checked');
  }

  new Tether({
    element: $edit_button,
    target: $video,
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

function lfInitVideos() {
  /*
    Loop through all videos
  */

  $('.-x-video').each(function() {
    var $video = $(this);
    lfInitVideo($video);
  });

  lfParseVideos(true);

  /* 
    Open modal to configure video
  */

  $('body').on('click', '.-x-el-video-update', function() {
    var el_class = $(this).parents('.-x-el-video-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $el = $('.' + el_class);

      // Is immediate parent a link?
      //var link = ($el.parent('a').length > 0) ? 1 : 0;

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/video', el_class);
    }
  });

  /* 
    Video ratio
  */

  $('body').on('click', '.-x-el-video-ratio-select', function() {
    var el_class = $(this).parents('.-x-el-video-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      var selected_ratio = $(this).attr('data-x-ratio');

      // Unselect all
      $(this).parents('ul').find('.-x-el-video-ratio-select .-x-el-checkmark').removeClass('-x-checked');

      // Select size
      $(this).parents('ul').find('.-x-el-video-ratio-select[data-x-ratio=' + selected_ratio + '] .-x-el-checkmark').addClass('-x-checked');

      // Remove all size classes
      var $el = $('.' + el_class);
      $el.removeClass('embed-responsive-21by9 embed-responsive-16by9 embed-responsive-4by3 embed-responsive-1by1');

      // Add size class
      $el.addClass('embed-responsive-' + selected_ratio);

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });


  /* 
    Toggle video visibility
  */

  $('body').on('click', '.-x-el-video-visible', function() {
    var el_class = $(this).parents('.-x-el-video-edit-clone').attr('data-x-el');

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
  Duplicate video buttons and references
*/

function lfDuplicateBlockVideos($new_block) {

  // Loop through all videos in new block
  $new_block.find('.-x-video').each(function() {
    var timestamp = new Date().getTime();
    var $new_video = $(this);
    var video_class = $new_video.attr('data-x-el');

    lfInitVideo($new_video, video_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseVideos, 70);
}

/* 
  Loop through video settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseVideos(init) {
  var zIndex = 100;
  
  $('.-x-video').each(function() {
    var video_class = $(this).attr('data-x-el');
    var $video_settings = $('.-x-el-video-edit-clone[data-x-el=' + video_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $video_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $video_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $video_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}