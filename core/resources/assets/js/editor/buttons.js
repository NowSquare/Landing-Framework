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

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }, 200);
  });

  $('body').on('mouseenter', '.-lf-el-dropdown', function() {
    clearTimeout(lfMouseLeaveDropDown);
  });
});