function lfInitFabForms() {
  var $el = $(xTplFabForms).clone().appendTo('body');

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
    Save page
  */

  $('body').on('click', '.-x-el-fab-save', function() {
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

    // Post html
    var html = lfGetHtml();

    var jqxhr = $.ajax({
      url: _lang["url"] + '/forms/save',
      data: {sl: lf_sl, html: html, _token: lf_csrf_token},
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
    Publish page
  */

  $('body').on('click', '.-x-el-fab-publish-publish', function() {
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

    // Post html
    var html = lfGetHtml();

    var jqxhr = $.ajax({
      url: _lang["url"] + '/forms/publish',
      data: {sl: lf_sl, html: html, _token: lf_csrf_token},
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
    Unpublish page
  */

  $('body').on('click', '.-x-el-fab-publish-unpublish', function() {
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

    var jqxhr = $.ajax({
      url: _lang["url"] + '/forms/unpublish',
      data: {sl: lf_sl, _token: lf_csrf_token},
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
    $('.-x-el-inline-button-clone .-x-el-icon').toggle();

    // Toggle checkmark
    $(this).find('.-x-el-checkmark').toggleClass('-x-checked');
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Open QR
  */

  $('body').on('click', '.-x-el-fab-view-qr', function() {
    $('.-x-el-inline-button-clone .-x-el-icon').toggle();

    lfOpenModal(_lang["url"] + '/landingpages/editor/modal/qr?url=' + lf_published_url);

    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Settings
  */

  $('body').on('click', '.-x-el-fab-form-seo', function() {
    $('.-x-el-inline-button-clone .-x-el-icon').toggle();
    
    lfOpenModal(_lang["url"] + '/forms/editor/modal/seo?sl=' + lf_sl);
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });

  /* 
    Design
  */

  $('body').on('click', '.-x-el-fab-form-design', function() {
    $('.-x-el-inline-button-clone .-x-el-icon').toggle();
    
    lfOpenModal(_lang["url"] + '/forms/editor/modal/design?sl=' + lf_sl);
  
    // Hide dropdown
    $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
  });
}