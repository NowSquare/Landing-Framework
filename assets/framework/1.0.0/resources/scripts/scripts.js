var ladda_button;

/*
 * This function is called when the editor duplicates a block.
 * All template relevant stuff that needs to be re-bound and
 * re-initiated can be put here.
 */

function lfDuplicateBlockHook($new_block) {

  /*
   * Social buttons
   */

  lfParseSocialButtons($new_block);
}

/*
 * This function is called when the editor inserts a block.
 */

function lfInsertBlockHook($new_block) {

  /*
   * Social buttons
   */

  lfParseSocialButtons($new_block);
}

/*
 * Social buttons
 * https://jonsuh.com/blog/social-share-links/
 */

function lfParseSocialButtons($new_block) {
  $container = (typeof $new_block !== 'undefined') ? $new_block : $('body');

  var url = $container.find('[data-url]').attr('data-url');
  
  url = (typeof url !== typeof undefined && url !== false && url != '') ? url : window.location.href;

  var title = $container.find('[data-title]').attr('data-title');
  title = (typeof title !== typeof undefined && title !== false && title != '') ? title : $(document).find('title').text();

  var description = $container.find('[data-description]').attr('data-description');
  description = (typeof description !== typeof undefined && description !== false && description != '') ? description : $('meta[name=description]').attr('content');
  description = (typeof description !== typeof undefined && description !== false && description != '') ? description : '';

  if ($container.find('.btn-twitter').length) {
    $container.find('.btn-twitter').each(function() {

      var hashtags = $(this).closest('[data-url]').attr('data-hashtags');
      hashtags = (typeof hashtags !== typeof undefined && hashtags !== false && hashtags != '') ? hashtags : '';

      var via = $(this).closest('[data-via]').attr('data-via');
      via = (typeof via !== typeof undefined && via !== false && via != '') ? via : '';

      // Build query string
      var qs = {};
      qs.text = (description != '') ? description : title;
      qs.url = url;
      if (via != '') qs.via = via;
      if (hashtags != '') qs.hashtags = hashtags;

      var share = 'https://twitter.com/intent/tweet?' + $.param(qs);

      $(this).unbind();
      $(this).on('click', function(e) {
        e.preventDefault();
        windowPopup(share, 500, 300);
      });

      //$(this).attr('href', share);
    });
  }

  if ($container.find('.btn-facebook').length) {
    $container.find('.btn-facebook').each(function() {

      // Build query string
      var qs = {};
      qs.url = url;

      var share = 'https://www.facebook.com/sharer/sharer.php?' + $.param(qs);

      $(this).unbind();
      $(this).on('click', function(e) {
        e.preventDefault();
        windowPopup(share, 500, 200);
      });

      //$(this).attr('href', share);
    });
  }

  if ($container.find('.btn-gplus').length) {
    $container.find('.btn-gplus').each(function() {

      // Build query string
      var qs = {};
      qs.url = url;

      var share = 'https://plus.google.com/share?' + $.param(qs);

      $(this).unbind();
      $(this).on('click', function(e) {
        e.preventDefault();
        windowPopup(share, 500, 300);
      });

      //$(this).attr('href', share);
    });
  }

  if ($container.find('.btn-linkedin').length) {
    $container.find('.btn-linkedin').each(function() {

      // Build query string
      var qs = {};
      qs.mini = 'true';
      qs.url = url;
      qs.source = url;
      qs.title = title;
      if (description != '') qs.summary = description;

      var share = 'https://www.linkedin.com/shareArticle?' + $.param(qs);

      $(this).unbind();
      $(this).on('click', function(e) {
        e.preventDefault();
        windowPopup(share, 550, 500);
      });

      //$(this).attr('href', share);
    });
  }

  if ($container.find('.btn-pinterest').length) {
    $container.find('.btn-pinterest').each(function() {

      var media = $(this).closest('[data-media]').attr('data-media');
      media = (typeof media !== typeof undefined && media !== false && media != '') ? media : '';

      var hashtags = $(this).closest('[data-hashtags]').attr('data-hashtags');
      hashtags = (typeof hashtags !== typeof undefined && hashtags !== false && hashtags != '') ? hashtags : '';

      // Build query string
      var qs = {};
      qs.url = url;
      if (media != '') qs.media = media;
      qs.description = (description != '') ? description : title;
      if (hashtags != '') qs.hashtags = hashtags;

      var share = 'https://www.pinterest.com/pin/create/button/?' + $.param(qs);

      $(this).unbind();
      $(this).on('click', function(e) {
        e.preventDefault();
        windowPopup(share, 800, 600);
      });

      //$(this).attr('href', 'https://www.pinterest.com/pin/create/button/?' + $.param(qs));
    });
  }
}

function windowPopup(url, width, height) {
  // Calculate the position of the popup so
  // it’s centered on the screen.
  var left = (window.screen.width / 2) - (width / 2),
      top = (window.screen.height / 2) - (height / 2);

  left = (window.screen.availLeft + (window.screen.availWidth / 2)) - (width / 2);
  top = (window.screen.availTop + (window.screen.availHeight / 2)) - (height / 2);

  window.open(
    url,
    "",
    "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left
  );
}

$(function($) {

	/*
	 * Ajax forms
	 */

  bindAjaxForms();

	/*
	 * Form links
	 */

  bindAjaxFormLinks();

	/*
	 * Parse social links
	 */
  
  lfParseSocialButtons();

	/*
	 * Init vCard links
	 */
  
  initVCard();

  /*
   * Ekko Lightbox
   * http://ashleydw.github.io/lightbox/
   */

  $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    var self = $(this);
    $(this).attr('href', $(this).attr('src'));

    $('*[data-gallery]').each(function() {
      $(this).attr('href', $(this).attr('src'));
    });

    event.preventDefault();
    $(self).ekkoLightbox();
  });

  /*
   * Countdown timer
   */

	if ($('[data-countdown]').length) {
    $('[data-countdown]').each(function() {
      bindCountdown($(this));
    });
	}

});

var countdownTimer = new Array();

function bindCountdown($countdown) {
  var countdown = $countdown.attr('data-countdown');

  var dateTimePartsCountdown = countdown.split(' '),
      timePartsCountdown = dateTimePartsCountdown[1].split(':'),
      datePartsCountdown = dateTimePartsCountdown[0].split('-'),
      counterEnds;

  counterEnds = new Date(datePartsCountdown[0], parseInt(datePartsCountdown[1], 10) - 1, datePartsCountdown[2], timePartsCountdown[0], timePartsCountdown[1]);
  counterEnds = counterEnds.getTime();

  var end = counterEnds;
  
  var _second = 1000;
  var _minute = _second * 60;
  var _hour = _minute * 60;
  var _day = _hour * 24

  var _path = getElementPath($countdown);

  if (typeof countdownTimer[_path] !== 'undefined') {
    clearInterval(countdownTimer[_path]);
  }

  function showRemaining() {
    var now = new Date();
    var utc = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),  now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
    now = utc.getTime();
    var distance = end - now;

    var days = Math.floor(distance / _day);
    var hours = Math.floor( (distance % _day ) / _hour );
    var minutes = Math.floor( (distance % _hour) / _minute );
    var seconds = Math.floor( (distance % _minute) / _second );

    if (hours < 10) hours = '0' + hours;
    if (minutes < 10) minutes = '0' + minutes;
    if (seconds < 10) seconds = '0' + seconds;

    // Countdown is zero
    if (distance < 0) {
      days = '0';
      hours = '00';
      minutes = '00';
      seconds = '00';
    }

    $countdown.find('.day').text(days);
    $countdown.find('.hour').text(hours);
    $countdown.find('.minute').text(minutes);
    $countdown.find('.second').text(seconds);
  }

  countdownTimer[_path] = setInterval(showRemaining, 1000);
}

/*
 * Ajax form links
 */

function bindAjaxFormLinks() {

	if ($('[data-form]').length) {
    $('[data-form]').each(function(i) {
      var index = i;
      var $form_link = $(this);

      var form = $form_link.attr('data-form');

      var html = '<div class="-x-tmp modal modal-frame modal-form fade" tabindex="-1" role="dialog" id="formModal' + index + '" aria-hidden="true">' +
        '<div class="modal-dialog modal-lg">' +
          '<div class="modal-content">' +
            '<div class="modal-header">' +
              '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
              '</button>' +
            '</div>' +
            '<iframe src="about:blank" seamless="1" frameborder="0" style="width:100%;min-height:100px" id="formFrame' + index + '"></frame>' +
          '</div>' +
        '</div>' +
      '</div>';

      $('body').append(html);

      // Initialize the modal, but don't show yet
      $('#formModal' + index).modal({
        show: false,
        backdrop: 'static',
        keyboard: false
      });

      $form_link.off('click.form-modal').on('click.form-modal', function() {
        blockUI();
        var $modal = $('#formModal' + index);
        var $frame = $('#formFrame' + index);

        if ($frame.attr('src') == 'about:blank') {
          var qs = (typeof sl_lp !== 'undefined') ? '?sl_lp=' + sl_lp : '';
          $frame.attr('src', _trans['url'] + '/f/' + form + qs);

          // Set temp css to calculate iframe height
          $modal.css({
            visibility: 'hidden',
            display: 'block'
          });

          $frame.on('load', function() {
            var frame_height = parseInt($frame.contents().find('html').height());
            $frame.height(frame_height);

            $modal.attr('style', '');
            unblockUI();
            $modal.modal('show');
          });
        } else {
          // Set temp css to calculate iframe height
          $modal.css({
            visibility: 'hidden',
            display: 'block'
          });

          // Resize frame in case window size has changes when modal was hidden
          // Timeout is necessary because otherwise 0 height is calculated in some browsers.
          setTimeout(function() {
            var frame_height = parseInt($frame.contents().find('html').height());
            $frame.height(frame_height);

            $modal.attr('style', '');
            unblockUI();
            $('#formModal' + index).modal('show');
          }, 100);
        }

        $(window).resize(function() {
          var frame_height = parseInt($frame.contents().find('html').height());
          $frame.height(frame_height);
        });

      });
    });
  };
}

/*
 * Ajax forms
 */

function bindAjaxForms() {

  // Save cloned forms to freeze labels and placeholders for custom elements
  // This prevents visitors from chaning the dom with dev tools
	if ($('form.ajax').length) {
    var form = 0;
    var $f = [];
    $('form.ajax').each(function() {
      var $form = $(this);
      $form.attr('data-x-i', form);
      $f[form] = $form.clone(false);
      form++;
    });
  };

  $('form.ajax').validator().on('submit', function (e) {
    if (! e.isDefaultPrevented()) {
      var $form = $(this);
      var form = $form.attr('data-x-i');

      processAjaxForm($form, $f[form]);
      /*
      $('form.ajax').ajaxSubmit({
        dataType: 'json',
        beforeSerialize: beforeSerialize,
        success: formResponse,
        error: formResponse
      });
*/
    e.preventDefault();
    }
  });
}

function updateAjaxForms() {
  $('form.ajax').validator('update').on('submit', function (e) {
    if (! e.isDefaultPrevented()) {
      processAjaxForm($(this));
      /*
      $('form.ajax').ajaxSubmit({
        dataType: 'json',
        beforeSerialize: beforeSerialize,
        success: formResponse,
        error: formResponse
      });
*/
      e.preventDefault();
    }
  });
}

function processAjaxForm($form, $clone) {

  var $btn = $form.find('[type=submit]');

  if ($btn.is('[class*=btn-outline]')) {
    $btn.attr('data-spinner-color', $btn.css('border-top-color'));
  } else {
    $btn.attr('data-spinner-color', $btn.css('color'));
  }

	ladda_button = $btn.ladda();

    // Loading state
	ladda_button.ladda('start');

  if (typeof lf_demo === 'undefined') {
    var f = formSerialize($form, $clone);

    var jqxhr = $.ajax({
      url: _trans['url'] + "/f/post",
      data: {
        f: f, 
        _token: _trans['csrf']
      },
      method: 'POST'
    })
    .done(function(data) {
      if (typeof data.redir !== 'undefined') {
        window.top.location.href = data.redir;
      } else {
        swal({
          title: data.title,
          text: data.text,
          confirmButtonColor: $btn.css('border-top-color'),
          confirmButtonText: _trans['ok'],
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false
        }).then(function (result) {

          // Reset form
          if (data.success) {
            $form.resetForm();
          }

          // Loading state
          ladda_button.ladda('stop');

        }, function (dismiss) {
          // Do nothing on cancel
          // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
        });
      }
    })
    .fail(function(e) {
      alert('Request failed, please try again (' + e.textStatus + ')');
    })
    .always(function() {
      ladda_button.ladda('stop');
    });

  } else {
    swal({
      title: _trans['form_post_demo_title'],
      text: _trans['form_post_demo_text'],
      confirmButtonColor: $btn.css('border-color'),
      confirmButtonText: _trans['ok'],
      allowOutsideClick: false
    }).then(function (result) {

      // Reset form
      $form.resetForm();

      // Loading state
      ladda_button.ladda('stop');

    }, function (dismiss) {
      // Do nothing on cancel
      // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
    });
  }
}

function formSerialize($form, $clone) {
  var custom_vars = {};
  var form_vars = {};

	if ($form.find('.form-group').length) {
    $form.find('.form-group').each(function(i) {

      var $cloneGroup = $($clone.find('.form-group')[i]);
      var $cloneControl = $cloneGroup.find('.form-control');

      var $formGroup = $(this);
      var $formControl = $formGroup.find('.form-control');

      var type, val;
      var name = $cloneControl.attr('name');

      var label = $cloneGroup.find('label').html();
      label = (typeof label !== typeof undefined && label !== false) ? label : '';

      var placeholder = $cloneControl.attr('placeholder');
      placeholder = (typeof placeholder !== typeof undefined && placeholder !== false) ? placeholder : '';

      var reference = (label != '') ? label : placeholder;

      if (typeof name !== 'undefined') {
        type = 'form-control';
        val = $formControl.val();
        if (name == 'email') {
          reference = 'email';
        }
      } else if ($formGroup.find('input[type=radio]').length) {
        type = 'radio';
        name = $formGroup.find('input[type=radio]').attr('name');
        val = $formGroup.find('input[type=radio]:checked').val();
      } else if ($formGroup.find('input[type=checkbox]').length) {
        type = 'checkbox';
        name = $formGroup.find('input[type=checkbox]').attr('name');
        var val = [];
        $formGroup.find('input[type=checkbox]:checked').each(function() {
          val.push($(this).val());
        });
      } else {
        name = '';
      }

      if (name != '') {
        if (name.indexOf('[]') >= 0) {
          // It's a custom var
          custom_vars[reference] = val;
        } else {
          // It's a form var
          form_vars[name] = val;
        }
      }
    });
  }

  return {
    'sl_lp': (typeof sl_lp !== 'undefined') ? sl_lp : '',
    'sl_f': (typeof sl_f !== 'undefined') ? sl_f : '',
    'c': custom_vars,
    'f': form_vars
  };
}

/*
 * BlockUI
 */

function blockUI(el) {
  if (typeof el === 'undefined') {
    $.blockUI({
      message: '<div class="loader"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS: {
        backgroundColor: '#000'
      },
      css: {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  } else {
    $(el).block({
      message: '<div class="loader loader-xs"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS: {
        backgroundColor: '#000',
        opacity: 0.1,
      },
      css: {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  }
}

/*
 * unblockUI
 */

function unblockUI(el) {
  if (typeof el === 'undefined') {
    $.unblockUI();
  } else {
    $(el).unblock();
  }
}

/*
 * https://stackoverflow.com/questions/2068272/getting-a-jquery-selector-for-an-element/2068381#2068381
 */

function getElementPath($el) {
  var path, node = $el;
  while (node.length) {
    var realNode = node[0], name = realNode.localName;
    if (!name) break;
    name = name.toLowerCase();

    var parent = node.parent();

    var sameTagSiblings = parent.children(name);
    if (sameTagSiblings.length > 1) { 
      allSiblings = parent.children();
      var index = allSiblings.index(realNode) + 1;
      if (index > 1) {
        name += ':nth-child(' + index + ')';
      }
    }

    path = name + (path ? '>' + path : '');
    node = parent;
  }

  return path;
}

/*
 * vCard
 */

function initVCard() {
  $('body').on('click', '.vcard-link', function() {
    var vcard = $(this).attr('data-vcard-data');
    vcard = JSON.parse(vcard);
    var url = _trans['url'] + "/vcard?" + $.param(vcard, true);

    document.location = url;
  });
}