/*
  Generic button related functions.
*/

$(function() {
  /*
    Show dropdown on click
  */
  
  $('body').on('click', '.-x-el-inline-button-clone', function() {
    var $block_edit_dropdown = $(this).find('.-x-el-dropdown');

    if (typeof $block_edit_dropdown !== typeof undefined && $block_edit_dropdown !== false) {
      $block_edit_dropdown.css('cssText', 'display: block !important;');

      // Set z-index of all buttons temporary to a high value
      $(this).attr('data-x-zIndex', $(this).css('z-index'));
      $(this).css('cssText', 'z-index: 1000000 !important;');

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  /*
    Hide dropdown on mouse leave with delay
  */

  var lfMouseLeaveDropDown;

  $('body').on('mouseleave', '.-x-el-dropdown', function() {
    var that = this;

    lfMouseLeaveDropDown = setTimeout( function(){
      $(that).css('cssText', 'display: none !important;');

      // Set z-index back to old value
      var $button = $(that).parents('.-x-el-inline-button-clone');
      $button.css('cssText', 'z-index: ' + $button.attr('data-x-zIndex') + ' !important;');
      $button.attr('data-x-zIndex', null);

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }, 200);
  });

  $('body').on('mouseenter', '.-x-el-dropdown', function() {
    clearTimeout(lfMouseLeaveDropDown);
  });
});