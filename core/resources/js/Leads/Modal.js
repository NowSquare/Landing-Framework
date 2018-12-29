// Lead Modal
let CryptoJS = require('crypto-js');
let ouibounce = require('ouibounce');

let leadModal = {

  /*
   * Default settings
   */
  settings: {
    token: null, // Token
    id: 0, // Modal id
    host: null, // Host where lead platform is hosted
    modalUrl: null, // Default url for modal
    locale: null, // Language code
    cssPrefix: '-lm-', // CSS class/id prefix
    trigger: 'onload', // How / when is the modal triggered ('onload', 'onscroll', 'onleave')
    delay: 0, // How much delay after trigger
    scrollTop: 0, // Percentage visitor has to scroll down before 'onscroll' trigger fires
    allowedHosts: [], // Array of hosts where the modal is allowed, leave empty to allow all hosts
    allowedPaths: [], // Array of paths where the modal is allowed, leave empty to allow all paths (/ = root, other paths are prefixed with /)
    allowedReferrerHosts: [], // Array of referrer hosts where the modal is allowed, leave empty to allow all hosts
    allowedReferrerPaths: [], // Array of referrer paths where the modal is allowed, leave empty to allow all paths (/ = root, other paths are prefixed with /)
    ignoreAfterCloses: 1, // Modal isn't triggered anymore after N closes
    fullscreen: false, // If fullscreen, no close button will show
    backdropVisible: true, // Show backdrop and disable scrolling, or not
    backdropBgColor: 'rgba(0,0,0,0.85)', // CSS color or null
    backdropImage: null, // Path to image or null
    backdropAnimationShow: 'fadeIn', // CSS animation classname
    backdropAnimationDuration: 800, // CSS animation duration in MS
    backdropAnimationHide: 'fadeOut', // CSS animation classname
    backdropAnimationHideDuration: 500, // CSS animation duration
    backdropDelay: 0, // Time it takes for backdrop to animate in
    showLoader: true, // Show loader true|false
    loaderColor: null, // Loader color (e.g. '#ff0000')
    closeBtnMargin: 15, // Margin close button
    closeBtnColor: '#000000', // Close button color
    contentPosition: 'center', // 'center' or 'right-bottom'
    contentBorderMargin: 15, // Margin in case positions is 'right-bottom'
    contentWidth: null, // Content width (e.g. 400) or null
    contentHeight: null, // Content height (e.g. 400) or null
    contentUnit: 'px', // Content unit (e.g. 'px', 'rem')
    contentAnimationShow: 'bounceInDown', // CSS animation classname
    contentAnimationHide: 'fadeOut', // CSS animation classname
    contentClasses: null, // Extra classes for modal content (e.g. 'shadow--16dp')
    contentStyle: null, // Style for content (e.g. 'background-color:#fff;border-radius:4rem;')
    contentFrameStyle: null, // Style for content object frame (e.g. 'margin:4rem;')
    contentDelay: -200, // Time it takes for content to animate in (after backdrop animation is ready)
    animationOffset: 250, // Used to hide/show elements after/before they are removed or added, to fix JS / CSS timing issues
    cookieNamePrefix: 'lm-', // Cookie name prefix
    cookieExpirationDays: 30, // Days after which cookie expires
    cookiePath: '/', // Cookie path
    appKey: 'base64:ahvecFmvHiXzIKQAlPfnhXKlHaqt2Y/wVdJZ354vMmk=', // Laravel key (not the same as in the .env, any key is fine)
  },

  /* Initialize modal */
  init: function(token, host, callback) {
    // Set globals
    this.settings.token = token;
    this.settings.host = host;

    // Load CSS
    var cssId = leadModal.settings.cssPrefix + 'style';
    if (! document.getElementById(cssId)) {
      var head  = document.getElementsByTagName('head')[0];
      var link  = document.createElement('link');
      link.id   = cssId;
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = leadModal.settings.host + '/modal/style.css';
      link.media = 'all';
      head.appendChild(link);
    }

    // Load settings
    this.loadSettings(callback);
  },

  /* Data loaded, ready */
  ready: function(callback) {
    callback(this, leadModal.settings);
  },

  /*
   * Load modal settings and merge with default settings
   */
  loadSettings: function(callback) {
    if (leadModal.settings.token !== null) {
      // Get language
      let userLang = navigator.language || navigator.userLanguage;

      // Load settings
      leadModal.getJSON(leadModal.settings.host + '/modal/settings?token=' + this.settings.token + '&lang=' + userLang.slice(0,2) + '&host=' + encodeURIComponent(window.location.host) + '&path=' + encodeURIComponent(window.location.pathname), function(err, data) {
        if (err !== null) {
          console.log('[leadModal.loadSettings] Something went wrong: ' + err);
        } else {
          // All data loaded, merge
          leadModal.settings = {...leadModal.settings, ...data};
          // Set cookie name
          leadModal.cookieName = leadModal.settings.cookieNamePrefix + leadModal.settings.token + leadModal.settings.id;
          // Callback
          leadModal.ready(callback);
        }
      });
    } else {
      // Callback
      leadModal.ready(callback);
    }
  },

  /* Start modal */
  start: function() {
    // Token is null, class called for test purposes
    if (leadModal.settings.token != null) {

     // Check if host is allowed
      if (leadModal.settings.allowedHosts.length > 0) {
        let found = false;
        for (let i = 0; i < leadModal.settings.allowedHosts.length; i++) {
          if (window.location.host == leadModal.settings.allowedHosts[i]) found = true;
        }
        if (! found) return;
      }
 
      // Check if path is allowed
      if (leadModal.settings.allowedPaths.length > 0) {
        let found = false;
        for (let i = 0; i < leadModal.settings.allowedPaths.length; i++) {
          if (window.location.pathname == leadModal.settings.allowedPaths[i]) found = true;
        }
        if (! found) return;
      }

      // Get referrer parts
      let referrer = document.createElement('a');
      referrer.href = document.referrer;

      // Check if referrer host is allowed
      if (leadModal.settings.allowedReferrerHosts.length > 0) {
        let found = false;
        for (let i = 0; i < leadModal.settings.allowedReferrerHosts.length; i++) {
          if (referrer.host == leadModal.settings.allowedReferrerHosts[i]) found = true;
        }
        if (! found) return;
      }
 
      // Check if path is allowed
      if (leadModal.settings.allowedReferrerPaths.length > 0) {
        let found = false;
        for (let i = 0; i < leadModal.settings.allowedReferrerPaths.length; i++) {
          if (referrer.pathname == leadModal.settings.allowedReferrerPaths[i]) found = true;
        }
        if (! found) return;
      }

      // Cleanup
      referrer = null;

      let modalCookie = leadModal.getCookie(leadModal.cookieName);

      if (modalCookie != null) {
        modalCookie = JSON.parse(modalCookie);
        if (parseInt(leadModal.settings.ignoreAfterCloses) > 0 && parseInt(modalCookie.closes) >= parseInt(leadModal.settings.ignoreAfterCloses)) return;
      }

      // Check for trigger and delay
      if (leadModal.settings.trigger == 'onscroll') {
        window.addEventListener('scroll', scrollListener, false);

        function scrollListener() {
          let h = document.documentElement, 
              b = document.body,
              st = 'scrollTop',
              sh = 'scrollHeight';

          let percent = parseInt((h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100);

          if (percent >= parseInt(leadModal.settings.scrollTop)) {
            setTimeout(triggerModal, parseInt(leadModal.settings.delay));
            window.removeEventListener('scroll', scrollListener, false);
          }
        }
      } else if (leadModal.settings.trigger == 'onleave') {
        ouibounce(null, {
          cookieExpire: 0,
          callback: function() { 
            setTimeout(triggerModal, parseInt(leadModal.settings.delay));
          }
        });
      } else {
        // onload
        setTimeout(triggerModal, parseInt(leadModal.settings.delay));
      }
    } else { // leadModal.settings.token == null
      triggerModal();
    }

    function triggerModal() {
      let body, backdrop, backdropClasses, loader, content, btnClose;
      let contentClasses = '';

      /*
       * Remove existing instance if found
       */
      if (document.getElementById(leadModal.settings.cssPrefix + 'root') !== null) {
        // Delete root
        let root = document.getElementById(leadModal.settings.cssPrefix + 'root');
        root.parentNode.removeChild(root);

        // Remove body attribute
        body = document.getElementsByTagName("BODY")[0];
        body.removeAttribute('data' + leadModal.settings.cssPrefix + 'body');
      }

      /*
       * Update body tag to prevent scrolling and z-index issues
       */
      if (leadModal.settings.backdropVisible) {
        body = document.getElementsByTagName("BODY")[0];
        body.setAttribute('data' + leadModal.settings.cssPrefix + 'body', '1');
      }

      /*
       * Root element
       */
      let root = document.createElement('div');
      root.setAttribute('id', leadModal.settings.cssPrefix + 'root');
      document.body.appendChild(root);

      /*
       * Start modal animations
       */

      // Add backdrop
      setTimeout(function() {
        backdrop = document.createElement('div');
        let cssText = '';
        if (leadModal.settings.backdropBgColor !== null) cssText = 'background-color:' + leadModal.settings.backdropBgColor + ';';
        if (! leadModal.settings.backdropVisible) cssText = 'background-color:transparent;pointer-events:none;';
        if (leadModal.settings.backdropImage !== null) cssText += 'background-image:url(\'' + leadModal.settings.backdropImage + '\');';
        backdrop.style.cssText = cssText + ';overflow:hidden';
        backdropClasses = (leadModal.settings.contentPosition == 'right-bottom') ? leadModal.settings.cssPrefix + 'right-bottom ' : '';

        backdrop.setAttribute('class', backdropClasses + leadModal.settings.cssPrefix + 'backdrop ' + leadModal.settings.cssPrefix + leadModal.settings.backdropAnimationShow);
        root.appendChild(backdrop);

        // Enable scroll after animation
        setTimeout(function() {
          backdrop.style.cssText = cssText;
        }, 1000 + parseInt(leadModal.settings.backdropDelay + leadModal.settings.backdropAnimationDuration));

        // Loader
        if (leadModal.settings.showLoader) {
          let loaderCssText = (leadModal.settings.loaderColor == null) ? '' : ' style="border-color: ' + leadModal.settings.loaderColor + ' transparent transparent transparent"';
          loader = document.createElement('div');
          loader.setAttribute('class', '' + leadModal.settings.cssPrefix + 'loader');
          loader.innerHTML = '<div' + loaderCssText + '></div><div' + loaderCssText + '></div><div' + loaderCssText + '></div><div' + loaderCssText + '></div>';
          backdrop.appendChild(loader);
        }
      }, leadModal.settings.backdropDelay);

      // Add content
      setTimeout(function() {
        content = document.createElement('div');
        if (leadModal.settings.fullscreen) contentClasses += leadModal.settings.cssPrefix + 'fullscreen ';

        let contentStyle = (leadModal.settings.contentStyle == null) ? '' : leadModal.settings.contentStyle;
        if (! leadModal.settings.backdropVisible) contentStyle += ';pointer-events:all;';
        if (leadModal.settings.contentPosition == 'right-bottom') contentStyle += ';margin-right:' + leadModal.settings.contentBorderMargin + 'px;margin-bottom:' + leadModal.settings.contentBorderMargin + 'px;';

        let contentFrameStyle = (leadModal.settings.contentFrameStyle == null) ? '' : leadModal.settings.contentFrameStyle;

        let classes = '';
        if (leadModal.settings.contentWidth != null) {
          contentStyle += 'width:' + (parseInt(leadModal.settings.contentWidth)) + leadModal.settings.contentUnit + ';';
          contentFrameStyle += ';min-width:' + leadModal.settings.contentWidth + leadModal.settings.contentUnit + ';width:' + leadModal.settings.contentWidth + leadModal.settings.contentUnit + ';';
        }
        if (leadModal.settings.contentHeight != null) {
          contentStyle += 'height:' + (parseInt(leadModal.settings.contentHeight)) + leadModal.settings.contentUnit + ';';
          contentFrameStyle += ';min-height:' + leadModal.settings.contentHeight + leadModal.settings.contentUnit + ';height:' + leadModal.settings.contentHeight + leadModal.settings.contentUnit + ';';
        }

        content.style.cssText = contentStyle + ';position: absolute !important;top: -9999px !important;left: -9999px !important;';
        content.setAttribute('class', '' + leadModal.settings.cssPrefix + 'content ' + contentClasses);

        backdrop.appendChild(content);

        // Add object that loads html page
        let frame = document.createElement('object');
        frame.style.cssText = contentFrameStyle;
        frame.setAttribute('type', 'text/html');
        frame.setAttribute('class', leadModal.settings.contentClasses);

        // Only check status if in test mode
        if (leadModal.settings.token === null) {
          let xhr = new XMLHttpRequest();
          xhr.open('GET', leadModal.settings.modalUrl, true);
          xhr.onload = function() {
            let status = xhr.status;
            if (status === 200 || status === 302) {
              frame.setAttribute('data', leadModal.settings.modalUrl);
              content.appendChild(frame);
            } else {
              frame.setAttribute('data', leadModal.settings.host + '/modal/get?locale=' + leadModal.settings.locale);
              content.appendChild(frame);
            }
          };
          xhr.onerror = function() {
            frame.setAttribute('data', leadModal.settings.host + '/modal/get?locale=' + leadModal.settings.locale);
            content.appendChild(frame);
          };
          xhr.send();          
        } else {
          frame.setAttribute('data', leadModal.settings.modalUrl);
          content.appendChild(frame);
        }

        frame.onload = function() {
          if (leadModal.settings.showLoader) {
            loader.parentNode.removeChild(loader);
          }
          content.style.cssText = contentStyle;
          content.setAttribute('class', '' + leadModal.settings.cssPrefix + 'content ' + contentClasses + leadModal.settings.cssPrefix + leadModal.settings.contentAnimationShow);
          setTimeout(function() {
            btnClose.style.cssText = 'display:block;margin:' + leadModal.settings.closeBtnMargin + 'px';
            //btnClose.setAttribute('class', leadModal.settings.cssPrefix + 'btn-close + ' ' + leadModal.settings.cssPrefix + 'fadeIn');
          }, 300);
        }

        frame.onerror = function() {
          frame.setAttribute('data', leadModal.settings.host + '/modal/get?locale=' + leadModal.settings.locale);
          frame.onload();
        }

        // Close button
        btnClose = document.createElement('button');
        btnClose.setAttribute('id', '' + leadModal.settings.cssPrefix + 'btn-close');
        btnClose.setAttribute('class', leadModal.settings.cssPrefix + 'btn-close ');
        btnClose.style.cssText = 'display:none;margin:' + leadModal.settings.closeBtnMargin + 'px';
        //content.appendChild(btnClose);
        content.insertBefore(btnClose, document.getElementById(leadModal.settings.cssPrefix + 'frame'));
        let btnCloseInner = document.createElement('span');
        btnClose.appendChild(btnCloseInner);

        btnClose.pseudoStyle('before', 'background-color', leadModal.settings.closeBtnColor);
        btnClose.pseudoStyle('after', 'background-color', leadModal.settings.closeBtnColor);

        btnClose.addEventListener('click', lpClose);
      }, parseInt(leadModal.settings.backdropDelay + leadModal.settings.backdropAnimationDuration + leadModal.settings.contentDelay));

      /*
       * Close modal
       */
      function lpClose() {
        // Update / set cookie
        let modalCookie = leadModal.getCookie(leadModal.cookieName);
        let cookieVals = {};

        if (modalCookie == null) {
          cookieVals.closes = 1;
        } else {
          modalCookie = JSON.parse(modalCookie);
          cookieVals.closes = parseInt(modalCookie.closes) + 1;
        }

        leadModal.setCookie(leadModal.cookieName, JSON.stringify(cookieVals), leadModal.settings.cookieExpirationDays, leadModal.settings.cookiePath);

        backdrop.setAttribute('class', backdropClasses + leadModal.settings.cssPrefix + 'backdrop ' + leadModal.settings.cssPrefix + leadModal.settings.backdropAnimationHide);
        content.setAttribute('class', '' + leadModal.settings.cssPrefix + 'content ' + contentClasses + ' ' + leadModal.settings.cssPrefix + leadModal.settings.contentAnimationHide);

        setTimeout(function() {
          root.parentNode.removeChild(root);
          if (leadModal.settings.backdropVisible) {
            body.removeAttribute('data' + leadModal.settings.cssPrefix + 'body');
          }
        }, parseInt(leadModal.settings.backdropAnimationHideDuration - leadModal.settings.animationOffset));
      }
    }
  },

  /*
   * getJSON
   */
  getJSON: function(url, callback) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      let status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
  },

  /*
   * Get cookie
   */
  getCookie: function(name) {
    name = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0)==' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        let decrypted = CryptoJS.AES.decrypt(c.substring(name.length, c.length), leadModal.settings.appKey);
        return decrypted.toString(CryptoJS.enc.Utf8);
      }
    }
    return null;
  },

  /*
   * Set cookie
   */
  setCookie: function(name, value, expirationDays, path = '/') {
   let expirationDate = new Date();
   expirationDate.setDate(expirationDate.getDate() + expirationDays);
   document.cookie = encodeURIComponent(name) 
     + "=" + CryptoJS.AES.encrypt(value, leadModal.settings.appKey)
     + "; path=" + path
     + (!expirationDays ? "" : "; expires=" + expirationDate.toUTCString());
     ;
  }
};

var UID = {
	_current: 0,
	getNew: function(){
		this._current++;
		return this._current;
	}
};

HTMLElement.prototype.pseudoStyle = function(element, prop, value){
	var _this = this;
	var _sheetId = "pseudoStyles";
	var _head = document.head || document.getElementsByTagName('head')[0];
	var _sheet = document.getElementById(_sheetId) || document.createElement('style');
	_sheet.id = _sheetId;
	var className = "pseudoStyle" + UID.getNew();
	
	_this.className +=  " "+className; 
	
	_sheet.innerHTML += " ."+className+":"+element+"{"+prop+":"+value+"}";
	_head.appendChild(_sheet);
	return this;
};


module.exports = leadModal;