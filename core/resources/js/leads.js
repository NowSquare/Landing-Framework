/*
 * Global LP JS object
 */

'use strict';

import * as modal from './Leads/Modal.js';

// Get query string from this script include
var scripts = document.getElementsByTagName('script'); 
var lastScript = scripts[scripts.length - 1]; 

let token = getParameterByName('token', lastScript.src);

let script = document.createElement('a');
script.href = lastScript.src;
let host = script.protocol + '//' + script.host;

if (token !== null) {
  modal.init(token, host, function(modal, settings) {
    // Start modal
    modal.start();
  });
}

window.showLeadModal = function(modal_settings) {
  modal.init(null, host, function(modal, settings) {
    // Merge settings and start modal
    settings = {...settings, ...modal_settings};
    modal.settings = settings;
    modal.start();
  });
}

function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}