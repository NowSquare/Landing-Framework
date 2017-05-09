var xTplModal = '<div class="-x-editor-inline-modal-bg" style="z-index: 999990 !important;">' +
  '  <iframe class="-x-el-inline-modal -x-full -x-el-reset" src="about:blank" frameborder="0" allowtransparency="true" seamless></iframe>' +
  '</div>';

var xTplFab = '<div class="-x-el-inline-button -x-el-fab -x-el-reset" style="z-index: 999990 !important;">' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/dots-vertical.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/dots-vertical-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/dots-vertical.svg\';"' +
  '  >' +
  '  <ul class="-x-el-dropdown -x-el-dropdown-top-left -x-el-dropdown-fab -x-el-reset">' +
  '    <li class="-x-el-fab-publish"><a href="javascript:void(0);">' + _lang["publish"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
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
  '    <li class="-x-el-fab-view"><a href="javascript:void(0);">' + _lang["view"] + ' <div class="-x-el-caret"></div></a>' +
  '      <ul>' +
  '        <li class="-x-el-fab-view-online"><a href="javascript:void(0);">' + _lang["online"] + '</a></li>' +
  '        <li class="-x-el-fab-view-qr"><a href="javascript:void(0);">' + _lang["qr"] + '</a></li>' +
  '      </ul>' +
  '    </li>' + 
  '    <li class="separator"><hr></li>' +
  '    <li class="-x-el-fab-preview"><a href="javascript:void(0);">' + _lang["preview"] + '</a></li>' +
  '    <li class="-x-el-fab-save"><a href="javascript:void(0);">' + _lang["save_page"] + '</a></li>' +
  '  </ul>' +
  '</div>';

var xTplBlockButton = '<div class="-x-el-inline-button -x-el-block-edit -x-el-reset">' +
  '  <img src="' + _lang["url"] + '/assets/images/editor/icons/dots-horizontal.svg" class="-x-el-icon"' +
  '    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/dots-horizontal-hover.svg\';"' +
  '    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/dots-horizontal.svg\';"' +
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
  '   <li class="-x-el-list-edit"><a href="javascript:void(0);">' + _lang["update_list"] + '</a></li>' +
  ' </ul>' +
  '</div>';

var xTplImgButton = '<div class="-x-el-inline-button -x-el-img-edit -x-el-reset">' +
'  <img src="' + _lang["url"] + '/assets/images/editor/icons/image.svg" class="-x-el-icon"' +
'    onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/image-hover.svg\';"' +
'    onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/image.svg\';"' +
'  >' +
'  <ul class="-x-el-dropdown -x-el-reset">' +
'    <li class="-x-el-img-update"><a href="javascript:void(0);">' + _lang["update_image"] + '</a></li>' +
'    <li class="separator"><hr></li>' +
'    <li class="-x-el-img-remove"><a href="javascript:void(0);">' + _lang["remove"] + '</a></li>' +
'  </ul>' +
'</div>';

var xTplLinkButton = '<div class="-x-el-inline-button -x-el-link-edit -x-el-reset">' + 
  ' <img src="' + _lang["url"] + '/assets/images/editor/icons/link.svg" class="-x-el-icon"' +
  '   onMouseOver="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/link-hover.svg\';"' +
  '   onMouseOut="this.src = \'' + _lang["url"] + '/assets/images/editor/icons/link.svg\';"' +
  ' >' +
  ' <ul class="-x-el-dropdown -x-el-reset">' +
  '   <li class="-x-el-link-edit"><a href="javascript:void(0);">' + _lang["link_settings"] + '</a></li>' +
  '   <li class="-x-el-link-shape"><a href="javascript:void(0);">' + _lang["shape"] + ' <div class="-x-el-caret"></div></a>' +
  '     <ul>' +
  '       <li class="-x-el-link-shape-regular"><a href="javascript:void(0);">' + _lang["regular"] + '</a></li>' +
  '       <li class="-x-el-link-shape-pill"><a href="javascript:void(0);">' + _lang["pill"] + '</a></li>' +
  '     </ul>' +
  '   </li>' +
  ' </ul>' +
  '</div>';