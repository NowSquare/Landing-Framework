function lfInitFabEmails() {
  var $el = $(xTplFabEmails).clone().appendTo('body');

  // Set options
  $el.find('.-x-el-fab-position-right .-x-el-checkmark').addClass('-x-checked');

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
    Save email
  */

  $('body').on('click', '.-x-el-fab-save', function() {
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

    // Post html
    var html = lfGetHtml();

    // Other vars
    var subject = window.parent.document.getElementById('subject').value;
    var mailto = window.parent.document.getElementById('mailto');
    mailto = (mailto == null || typeof mailto === 'undefined') ? '' : mailto.value;
    var from_name = window.parent.document.getElementById('from_name').value;
    var from_email = window.parent.document.getElementById('from_email').value;

    var jqxhr = $.ajax({
      url: _lang["url"] + '/emailcampaigns/emails/save',
      data: { sl: lf_sl, html: html, subject: subject, mailto: mailto, from_name: from_name, from_email: from_email, _token: lf_csrf_token },
      method: 'POST'
    })
    .done(function(data) {
      if (data.success) {
        $.notify({
            title: _lang['notification'],
            text: data.msg + "&nbsp;&nbsp;",
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
      } else {
        alert(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
    });
  });

  /* 
    Change position
  */

  $('body').on('click', '.-x-el-fab-position-left, .-x-el-fab-position-right', function() {
    var $fab_button = $('.-x-el-fab');

    if ($(this).hasClass('-x-el-fab-position-left')) {
      // Move button to left
      $fab_button.addClass('-x-el-fab-left');
      $('.-x-el-dropdown-fab').removeClass('-x-el-dropdown-top-left').addClass('-x-el-dropdown-top-right');

      // Checkmark
      $('.-x-el-fab-position-right .-x-el-checkmark').removeClass('-x-checked');
      $('.-x-el-fab-position-left .-x-el-checkmark').addClass('-x-checked');
    } else {
      // Move button to right
      $fab_button.removeClass('-x-el-fab-left');
      $('.-x-el-dropdown-fab').removeClass('-x-el-dropdown-top-right').addClass('-x-el-dropdown-top-left');

      // Checkmark
      $('.-x-el-fab-position-left .-x-el-checkmark').removeClass('-x-checked');
      $('.-x-el-fab-position-right .-x-el-checkmark').addClass('-x-checked');
    }

    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Preview, toggle buttons
  */

  $('body').on('click', '.-x-el-fab-preview', function() {

    // Toggle tinyMCE visual aids
    emailEditor.execCommand('mceToggleVisualAid');

    // Toggle checkmark
    $(this).find('.-x-el-checkmark').toggleClass('-x-checked');
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Settings
  */

  $('body').on('click', '.-x-el-fab-email-settings', function() {    
    lfOpenModal(_lang["url"] + '/emailcampaigns/editor/modal/settings?sl=' + lf_sl);
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Test email
  */

  $('body').on('click', '.-x-el-fab-test-mail', function() {    
    lfOpenModal(_lang["url"] + '/emailcampaigns/editor/modal/test-email?sl=' + lf_sl);
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

}