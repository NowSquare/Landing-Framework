function lfInitFab() {
  var $el = $(xTplFab).clone().appendTo('body');

  /*
    Show fab dropdown on click
  */
  
  $('body').on('click', '.-x-el-fab', function() {
    var $fab_button = $(this);
    var $fab_dropdown = $('.-x-el-dropdown-fab');

    if (typeof $fab_dropdown !== typeof undefined && $fab_dropdown !== false) {

      $fab_dropdown.css('cssText', 'position: fixed !important;display: block !important;z-index: 1000000 !important;');

      if (! $fab_button.hasClass('-x-data-enabled')) {
        new Tether({
          element: $fab_dropdown,
          target: $fab_button,
          attachment: 'bottom left',
          offset: '-60px -60px',
          targetAttachment: 'top right',
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
        //$fab_dropdown.css('cssText', 'position: fixed !important;display: block !important;');
      } else {
        // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
        Tether.position();
      }
    }
  });
  
  /* 
    Save page
  */

  $('body').on('click', '.-x-el-fab-save', function() {
    var html = lfGetHtml();

    console.log(html);

    $.notify({
        title: _lang['notification'],
        text: _lang['save_succes'] + "&nbsp;&nbsp;",
        image: '<i class="fa fa-bell-o" aria-hidden="true"></i>'
      }, {
        style: 'metro',
        className: 'white', /* white, black, error, success, warning, info */
        globalPosition: 'top right',
        autoHide: true,
        clickToHide: true,
        autoHideDelay: 5000,
        showAnimation: 'fadeIn',
        showDuration: 200,
        hideAnimation: 'fadeOut',
        hideDuration: 200
    });
  });
  
  /* 
    Preview, toggle buttons
  */

  $('body').on('click', '.-x-el-fab-preview', function() {
    $('.-x-el-inline-button-clone .-x-el-icon').toggle();

    // Wait a little to prevent flashing of text before dropdown is closed
    setTimeout(function() {
      $('.-x-el-fab-preview-toggle').toggle();
    }, 200);
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave');
  });
}