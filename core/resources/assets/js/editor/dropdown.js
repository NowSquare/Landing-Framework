/*
  Generic button related functions.
*/

$(function() {
  /*
    Show dropdown on click
  */
  
  $('body').on('click', '.-lf-el-inline-button-clone', function() {
    var $block_edit_dropdown = $(this).find('.-lf-el-dropdown');

    if (typeof $block_edit_dropdown !== typeof undefined && $block_edit_dropdown !== false) {
      $block_edit_dropdown.css('cssText', 'display: block !important;');

      // Set z-index of all buttons temporary to a high value
      $(this).attr('data-lf-zIndex', $(this).css('z-index'));
      $(this).css('cssText', 'z-index: 1000000 !important;');

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  /*
    Hide dropdown on mouse leave with delay
  */

  var lfMouseLeaveDropDown;

  $('body').on('mouseleave', '.-lf-el-dropdown', function() {
    var that = this;

    lfMouseLeaveDropDown = setTimeout( function(){
      $(that).css('cssText', 'display: none !important;');

      // Set z-index back to old value
      var $button = $(that).parents('.-lf-el-inline-button-clone');
      $button.css('cssText', 'z-index: ' + $button.attr('data-lf-zIndex') + ' !important;');
      $button.attr('data-lf-zIndex', null);

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }, 200);
  });

  $('body').on('mouseenter', '.-lf-el-dropdown', function() {
    clearTimeout(lfMouseLeaveDropDown);
  });
});