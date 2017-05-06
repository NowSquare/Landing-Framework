/*
  Clone modal template to page
*/
var $lfModal;

function lfInitModal() {
  $lfModal = $(xTplModal).clone().appendTo('body');  
}

/*
  Open modal
*/

function lfOpenModal(src, el_class, size) {
  var refreshIntervalId = setInterval(function() {
    if(typeof $lfModal !== 'undefined'){

      // Query string
      var qs = '';

      // Close dropdown and reset z-index if el_class is defined
      if (typeof el_class !== 'undefined') {
        qs += (qs == '' && src.indexOf('?') == -1) ? '?' : '&';
        qs += 'el_class=' + el_class;
        var $button = $('[data-x-el=' + el_class + ']');
        var $dropdown = $button.find('.-x-el-dropdown');
        $dropdown.css('cssText', 'display: none !important;');

        // Set z-index back to old value
        $button.css('cssText', 'z-index: ' + $button.attr('data-x-zIndex') + ' !important;');
        $button.attr('data-x-zIndex', null);
      }

      $lfModal.find('.-x-el-inline-modal').attr('src', src + qs);

      // Set size class if defined
      if (typeof size !== 'undefined') {
        $lfModal.find('.-x-el-inline-modal').removeClass('-x-md -x-sm -x-full').addClass(size);
      } else {
        $lfModal.find('.-x-el-inline-modal').removeClass('-x-md -x-sm -x-full').addClass('-x-full');
      }

      // Show modal
      $lfModal.css('cssText', 'z-index: 999990 !important;display: block !important;');

      // Reposition tethered elements because $dropdown.css('cssText', ...); seems to reset position
      //Tether.position();

      clearInterval(refreshIntervalId);
    }
  }, 100);
}

/*
  Close modal
*/

function lfCloseModal() {
  $lfModal.find('.-x-el-inline-modal').attr('src', 'about:blank');
  $lfModal.css('cssText', 'z-index: 999990 !important;display: none !important;');
}