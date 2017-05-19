/*
  Generate semi-unique class
  to reference forms for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link dropdown to form (Tether).
*/

function lfInitForm($form, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-form-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplFormButton).clone().appendTo('body');
  } else {
    var form_class = unique_class;

    // New unique class
    var unique_class = '-x-data-form-' + timestamp;

    // Clone form and replace with new class
    $form.removeClass(form_class);
    $form.addClass(unique_class);
    $form.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-form-edit-clone[data-x-el=' + form_class + ']').clone().insertAfter('.-x-el-form-edit-clone[data-x-el=' + form_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $form.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'bottom right';

  var targetAttachment = $form.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $form.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

  var dropdownPosition = $form.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $form.addClass(unique_class);
  $form.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $form.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-form-edit').addClass('-x-el-form-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  /*
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-form-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-form-visible .-x-el-checkmark').addClass('-x-checked');
  }
  */

  new Tether({
    element: $edit_button,
    target: $form,
    attachment: attachment,
    offset: offset,
    targetAttachment: targetAttachment,
    classPrefix: '-x-data',
    /*
    constraints: [{
      to: 'scrollParent',
      attachment: 'together'
    }],*/
    optimizations: {
      moveElement: true,
      gpu: true
    }
  });
}

function lfInitForms() {

  /*
    Loop through all forms, generate semi-unique class
    to reference forms for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link dropdown to form (Tether).
  */

  $('.-x-form').each(function() {
    var $form = $(this);
    lfInitForm($form);
  });

  lfParseForms(true);

  /* 
    Open modal to configure form
  */

  $('body').on('click', '.-x-el-form-edit', function() {
    var el_class = $(this).parents('.-x-el-form-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/form', el_class);
    }
  });

  /* 
    Toggle form visibility
  * /

  $('body').on('click', '.-x-el-form-visible', function() {
    var el_class = $(this).parents('.-x-el-form-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Toggle .invisible class
      var $el = $('.' + el_class);
      $el.toggleClass('invisible');
      $(this).find('.-x-el-checkmark').toggleClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });*/
}

/* 
  Duplicate forms and references
*/

function lfDuplicateBlockForms($new_block) {

  // Loop through all forms in new block
  $new_block.find('.-x-form').each(function() {
    var timestamp = new Date().getTime();
    var $new_form = $(this);
    var form_class = $new_form.attr('data-x-el');

    lfInitForm($new_form, form_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseForms, 70);
}

/* 
  Loop through form settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseForms(init) {
  var zIndex = 200;
  
  $('.-x-form').each(function() {
    var form_class = $(this).attr('data-x-el');
    var $form_settings = $('.-x-el-form-edit-clone[data-x-el=' + form_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $form_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $form_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $form_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}