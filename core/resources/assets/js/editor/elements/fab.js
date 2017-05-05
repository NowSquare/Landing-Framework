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
    Export html
  */

  $('body').on('click', '.-x-el-fab-export', function() {
    lfGetHtml();
  });
}