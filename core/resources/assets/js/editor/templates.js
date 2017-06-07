var xTplModal = '<div class="-x-editor-inline-modal-bg" style="z-index: 59999 !important;">' +
  '  <iframe class="-x-el-inline-modal -x-full -x-el-reset" src="about:blank" frameborder="0" allowtransparency="true" seamless></iframe>' +
  '</div>';

var xTplFabLandingpages = '<div class="-x-el-inline-button -x-el-fab -x-el-reset" style="z-index: 59999 !important;">' +
  '  <span>' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/fab.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab.svg\';"' +
  '  >' +
  '  </span>' +
  '  <ul class="-x-el-dropdown -x-el-dropdown-top-left -x-el-dropdown-fab -x-el-reset">' +
  '    <li class="-x-el-fab-publish"><a href="javascript:void(0);">' + _lang["save"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-save"><a href="javascript:void(0);">' + _lang["save_page"] + '</a></li>' +
  '        <li class="-x-el-fab-publish-publish"><a href="javascript:void(0);">' + _lang["save_and_publish"] + '</a></li>' +
  '        <li class="separator"><hr></li>' +
  '        <li class="-x-el-fab-publish-unpublish"><a href="javascript:void(0);">' + _lang["unpublish"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="-x-el-fab-page"><a href="javascript:void(0);">' + _lang["page"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-page-seo"><a href="javascript:void(0);">' + _lang["seo"] + '</a></li>' +
  '        <li class="-x-el-fab-page-domain"><a href="javascript:void(0);">' + _lang["domain"] + '</a></li>' +
  '      </ul>' +
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-view"><a href="javascript:void(0);">' + _lang["view"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-preview"><a href="javascript:void(0);">' + _lang["preview"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '        <li class="separator"><hr></li>' +
  '        <li class="-x-el-fab-view-online"><a href="' + lf_published_url + '" target="_blank">' + _lang["online"] + '</a></li>' +
  '        <li class="-x-el-fab-view-qr"><a href="javascript:void(0);">' + _lang["qr"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="-x-el-fab-position"><a href="javascript:void(0);">' + _lang["position"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-position-left"><a href="javascript:void(0);">' + _lang["left"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '        <li class="-x-el-fab-position-right"><a href="javascript:void(0);">' + _lang["right"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-insert-block"><a href="javascript:void(0);">' + _lang["insert_block"] + '</a></li>' +
  '  </ul>' +
  '</div>';

var xTplFabForms = '<div class="-x-el-inline-button -x-el-fab -x-el-reset" style="z-index: 59999 !important;">' +
  '  <span>' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/fab.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab.svg\';"' +
  '  >' +
  '  </span>' +
  '  <ul class="-x-el-dropdown -x-el-dropdown-top-left -x-el-dropdown-fab -x-el-reset">' +
  '    <li class="-x-el-fab-publish"><a href="javascript:void(0);">' + _lang["save"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-save"><a href="javascript:void(0);">' + _lang["save_form"] + '</a></li>' +
  '        <li class="-x-el-fab-publish-publish"><a href="javascript:void(0);">' + _lang["save_and_publish"] + '</a></li>' +
  '        <li class="separator"><hr></li>' +
  '        <li class="-x-el-fab-publish-unpublish"><a href="javascript:void(0);">' + _lang["unpublish"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="-x-el-fab-form"><a href="javascript:void(0);">' + _lang["form"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-form-seo"><a href="javascript:void(0);">' + _lang["seo"] + '</a></li>' +
  '        <li class="-x-el-fab-form-design"><a href="javascript:void(0);">' + _lang["design"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-view"><a href="javascript:void(0);">' + _lang["view"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-preview"><a href="javascript:void(0);">' + _lang["preview"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '        <li class="separator"><hr></li>' +
  '        <li class="-x-el-fab-view-online"><a href="' + lf_published_url + '" target="_blank">' + _lang["online"] + '</a></li>' +
  '        <li class="-x-el-fab-view-qr"><a href="javascript:void(0);">' + _lang["qr"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="-x-el-fab-position"><a href="javascript:void(0);">' + _lang["position"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-position-left"><a href="javascript:void(0);">' + _lang["left"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '        <li class="-x-el-fab-position-right"><a href="javascript:void(0);">' + _lang["right"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-save"><a href="javascript:void(0);">' + _lang["save_form"] + '</a></li>' +
  '  </ul>' +
  '</div>';

var xTplFabEmails = '<div class="-x-el-inline-button -x-el-fab -x-el-reset" style="z-index: 59999 !important;">' +
  '  <span>' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/fab.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/fab.svg\';"' +
  '  >' +
  '  </span>' +
  '  <ul class="-x-el-dropdown -x-el-dropdown-top-left -x-el-dropdown-fab -x-el-reset">' +
  '    <li class="-x-el-fab-email-settings"><a href="javascript:void(0);">' + _lang["settings"] + '</a></li>' +
  '    <li class="-x-el-fab-save"><a href="javascript:void(0);">' + _lang["save_email"] + '</a></li>' +
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-send-mailing -x-el-disabled"><a href="javascript:void(0);">' + _lang["send_mailing"] + '</a></li>' +
  '    <li class="-x-el-fab-test-mail"><a href="javascript:void(0);">' + _lang["test_email"] + '</a></li>' +
/*  '    <li class="-x-el-fab-email"><a href="javascript:void(0);">' + _lang["email"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-email-settings"><a href="javascript:void(0);">' + _lang["settings"] + '</a></li>' +
  '      </ul>' +
  '    </li>' +
  '    <li class="separator"><hr></li>' + */
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-position"><a href="javascript:void(0);">' + _lang["position"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-position-left"><a href="javascript:void(0);">' + _lang["left"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '        <li class="-x-el-fab-position-right"><a href="javascript:void(0);">' + _lang["right"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-preview"><a href="javascript:void(0);">' + _lang["preview"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '  </ul>' +
  '</div>';

var xTplBlockButton = '<div class="-x-el-inline-button -x-el-block-edit -x-el-reset">' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/cube.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/cube-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/cube.svg\';"' +
  '  >' +
  '  <ul class="-x-el-dropdown -x-el-reset">' +
  '    <li class="-x-el-block-background"><a href="javascript:void(0);">' + _lang["background"] + '</a></li>' +
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-block-move"><a href="javascript:void(0);">' + _lang["move"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-block-move-up"><a href="javascript:void(0);">' + _lang["move_up"] + '</a></li>' +
  '        <li class="-x-el-block-move-down"><a href="javascript:void(0);">' + _lang["move_down"] + '</a></li>' +
  '      </ul>' +
  '    </li>' +
  '    <li class="-x-el-block-insert"><a href="javascript:void(0);">' + _lang["insert"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-block-insert-above"><a href="javascript:void(0);">' + _lang["above"] + '</a></li>' +
  '        <li class="-x-el-block-insert-below"><a href="javascript:void(0);">' + _lang["below"] + '</a></li>' +
  '      </ul>' +
  '    </li>' +
  '    <li class="-x-el-block-edit-menu"><a href="javascript:void(0);">' + _lang["edit"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-block-edit-duplicate"><a href="javascript:void(0);">' + _lang["duplicate"] + '</a></li>' +
  '        <li class="-x-el-block-edit-delete"><a href="javascript:void(0);">' + _lang["delete"] + '</a></li>' +
  '      </ul>' +
  '    </li>' +
  '  </ul>' +
  '</div>';

var xTplListButton = '<div class="-x-el-inline-button -x-el-list-edit -x-el-reset">' + 
  ' <img src="' + _lang["url"] + '/assets/images/editor/icons/layers.svg" class="-x-el-icon"' +
  '   onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/layers-hover.svg\';"' +
  '   onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/layers.svg\';"' +
  ' >' +
  ' <ul class="-x-el-dropdown -x-el-reset">' +
  '   <li class="-x-el-list-edit"><a href="javascript:void(0);">' + _lang["modify_list"] + '</a></li>' +
  '   <li class="separator"><hr></li>' +
  '   <li class="-x-el-list-visible"><a href="javascript:void(0);">' + _lang["visible"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  ' </ul>' +
  '</div>';

var xTplFormButton = '<div class="-x-el-inline-button -x-el-form-edit -x-el-reset">' + 
  ' <img src="' + _lang["url"] + '/assets/images/editor/icons/email.svg" class="-x-el-icon"' +
  '   onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/email-hover.svg\';"' +
  '   onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/email.svg\';"' +
  ' >' +
  ' <ul class="-x-el-dropdown -x-el-reset">' +
  '   <li class="-x-el-form-edit"><a href="javascript:void(0);">' + _lang["modify_form"] + '</a></li>' +
  ' </ul>' +
  '</div>';

var xTplIconButton = '<div class="-x-el-inline-button -x-el-icon-edit -x-el-reset">' + 
  ' <img src="' + _lang["url"] + '/assets/images/editor/icons/vector.svg" class="-x-el-icon"' +
  '   onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/vector-hover.svg\';"' +
  '   onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/vector.svg\';"' +
  ' >' +
  ' <ul class="-x-el-dropdown -x-el-reset">' +
  '   <li class="-x-el-icon-edit"><a href="javascript:void(0);">' + _lang["modify_icon"] + '</a></li>' +
  '   <li class="separator"><hr></li>' +
  '   <li class="-x-el-icon-size"><a href="javascript:void(0);">' + _lang["size"] + ' <div class="-x-el-caret"></div></a>' +
  '     <ul>' +
/*  '       <li class="-x-el-icon-size-select" data-x-size="xs"><a href="javascript:void(0);">' + _lang["XS"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="s"><a href="javascript:void(0);">' + _lang["S"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="m"><a href="javascript:void(0);">' + _lang["M"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="l"><a href="javascript:void(0);">' + _lang["L"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="xl"><a href="javascript:void(0);">' + _lang["XL"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="xxl"><a href="javascript:void(0);">' + _lang["XXL"] + ' <div class="-x-el-checkmark"></div></a></li>' +*/
  '       <li class="-x-el-icon-size-select" data-x-size="xs"><a href="javascript:void(0);">' + _lang["S"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="m"><a href="javascript:void(0);">' + _lang["M"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="xl"><a href="javascript:void(0);">' + _lang["L"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-icon-size-select" data-x-size="xxl"><a href="javascript:void(0);">' + _lang["XL"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '     </ul>' +
  '   </li>' +
  '   <li class="-x-el-icon-visible"><a href="javascript:void(0);">' + _lang["visible"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  ' </ul>' +
  '</div>';

var xTplImgButton = '<div class="-x-el-inline-button -x-el-img-edit -x-el-reset">' +
'  <img src="' + _lang["url"] + '/assets/images/editor/icons/image.svg" class="-x-el-icon"' +
'    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/image-hover.svg\';"' +
'    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/image.svg\';"' +
'  >' +
'  <ul class="-x-el-dropdown -x-el-reset">' +
'    <li class="-x-el-img-update"><a href="javascript:void(0);">' + _lang["modify_image"] + '</a></li>' +
'    <li class="separator"><hr></li>' +
'    <li class="-x-el-img-shadow"><a href="javascript:void(0);">' + _lang["shadow"] + ' <div class="-x-el-caret"></div></a>' +
'      <ul>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="none"><a href="javascript:void(0);">' + _lang["none"] + ' <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="mdl-shadow--2dp"><a href="javascript:void(0);">2 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="mdl-shadow--4dp"><a href="javascript:void(0);">4 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="mdl-shadow--6dp"><a href="javascript:void(0);">6 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="mdl-shadow--8dp"><a href="javascript:void(0);">8 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shadow-select" data-x-shadow="mdl-shadow--16dp"><a href="javascript:void(0);">16 <div class="-x-el-checkmark"></div></a></li>' +
'      </ul>' +
'    </li>' +
'    <li class="-x-el-img-shape"><a href="javascript:void(0);">' + _lang["shape"] + ' <div class="-x-el-caret"></div></a>' +
'      <ul>' +
'        <li class="-x-el-img-shape-select" data-x-shape="none"><a href="javascript:void(0);">' + _lang["none"] + ' <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shape-select" data-x-shape="rounded"><a href="javascript:void(0);">' + _lang["rounded"] + ' <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-img-shape-select" data-x-shape="img-thumbnail"><a href="javascript:void(0);">' + _lang["bordered"] + ' <div class="-x-el-checkmark"></div></a></li>' +
'      </ul>' +
'    </li>' +
'    <li class="-x-el-img-visible"><a href="javascript:void(0);">' + _lang["visible"] + ' <div class="-x-el-checkmark"></div></a></li>' +
'    <li class="-x-el-img-remove"><a href="javascript:void(0);">' + _lang["remove"] + '</a></li>' +
'  </ul>' +
'</div>';

var xTplVideoButton = '<div class="-x-el-inline-button -x-el-video-edit -x-el-reset">' +
'  <img src="' + _lang["url"] + '/assets/images/editor/icons/filmstrip.svg" class="-x-el-icon"' +
'    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/filmstrip-hover.svg\';"' +
'    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/filmstrip.svg\';"' +
'  >' +
'  <ul class="-x-el-dropdown -x-el-reset">' +
'    <li class="-x-el-video-update"><a href="javascript:void(0);">' + _lang["modify_video"] + '</a></li>' +
'    <li class="separator"><hr></li>' +
'    <li class="-x-el-video-ratio"><a href="javascript:void(0);">' + _lang["ratio"] + ' <div class="-x-el-caret"></div></a>' +
'      <ul>' +
'        <li class="-x-el-video-ratio-select" data-x-ratio="21by9"><a href="javascript:void(0);">21:9 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-video-ratio-select" data-x-ratio="16by9"><a href="javascript:void(0);">16:9 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-video-ratio-select" data-x-ratio="4by3"><a href="javascript:void(0);">4:3 <div class="-x-el-checkmark"></div></a></li>' +
'        <li class="-x-el-video-ratio-select" data-x-ratio="1by1"><a href="javascript:void(0);">1:1 <div class="-x-el-checkmark"></div></a></li>' +
'      </ul>' +
'   </li>' +
'  </ul>' +
'</div>';

var xTplLinkButton = '<div class="-x-el-inline-button -x-el-link-edit -x-el-reset">' + 
  ' <img src="' + _lang["url"] + '/assets/images/editor/icons/link.svg" class="-x-el-icon"' +
  '   onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/link-hover.svg\';"' +
  '   onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/link.svg\';"' +
  ' >' +
  ' <ul class="-x-el-dropdown -x-el-reset">' +
  '   <li class="-x-el-link-edit"><a href="javascript:void(0);">' + _lang["modify_link"] + '</a></li>' +
  '   <li class="separator"><hr></li>' +
  '   <li class="-x-el-link-size"><a href="javascript:void(0);">' + _lang["size"] + ' <div class="-x-el-caret"></div></a>' +
  '     <ul>' +
  '       <li class="-x-el-link-size-select" data-x-size="s"><a href="javascript:void(0);">' + _lang["S"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-link-size-select" data-x-size="m"><a href="javascript:void(0);">' + _lang["M"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-link-size-select" data-x-size="l"><a href="javascript:void(0);">' + _lang["L"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-link-size-select" data-x-size="xl"><a href="javascript:void(0);">' + _lang["XL"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '     </ul>' +
  '   </li>' +
  '   <li class="-x-el-link-shape"><a href="javascript:void(0);">' + _lang["shape"] + ' <div class="-x-el-caret"></div></a>' +
  '     <ul>' +
  '       <li class="-x-el-link-shape-regular"><a href="javascript:void(0);">' + _lang["default"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '       <li class="-x-el-link-shape-pill"><a href="javascript:void(0);">' + _lang["pill"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  '     </ul>' +
  '   </li>' +
  '   <li class="-x-el-link-visible"><a href="javascript:void(0);">' + _lang["visible"] + ' <div class="-x-el-checkmark"></div></a></li>' +
  ' </ul>' +
  '</div>';