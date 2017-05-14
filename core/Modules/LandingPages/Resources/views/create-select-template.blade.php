<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/landingpages">{{ trans('landingpages::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/landingpages/create">{{ trans('landingpages::global.category') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('landingpages::global.' . $category) }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/landingpages/create" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row grid">
    <div class="grid-sizer col-xs-4" style="display:none"></div>
<?php
$i=0;
foreach ($templates as $template) {
  $i++;
?>
    <div class="grid-item col-xs-6 col-sm-2 col-lg-2" style="max-width: 240px">

      <div class="grid-item-content portlet shadow-box box-option" data-template="{{ $template['dir'] }}">
        <div>
          <div>
            <a href="javascript:void(0);" class="onClickPreview">
              <img src="{{ $template['preview01'] }}" id="box-icon{{ $i }}" style="width:100%" alt="{{ $template['dir'] }}">
            </a>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-xs-6">
                <a href="javascript:void(0);" class="onClickPreview btn btn-success btn-block" title="{{ trans('global.preview') }}" data-toggle="tooltip"><i class="mi search"></i></a>
              </div>
              <div class="col-xs-6">
                <?php /*<a href="#/landingpages/create/{{ $category }}/{{ $template['dir'] }}" class="btn btn-sm btn-primary btn-block">{{ trans('global.select') }}</a>*/ ?>
                <a href="#/landingpages/editor/{{ \Platform\Controllers\Core\Secure::array2string(array('new' => $template['dir'])) }}" class="btn btn-primary btn-block" title="{{ trans('global.select') }}" data-toggle="tooltip"><i class="mi navigate_next"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
</div>

<script>
var $grid = $('.grid').masonry({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  transitionDuration: '0.2s'
});

$('.onClickPreview').on('click', function() {
  var template = $(this).parents('.grid-item-content').attr('data-template');

  $.colorbox({
    href: app_root + '/landingpages/preview/' + template,
    fastIframe: false,
    overlayClose: true,
    fixed: false,
    iframe: true,
    reposition: false,
    transition: 'none', 
    fadeOut: 0,
    onOpen:function() {
      $('#colorbox').addClass('colorbox-xl');
    },
    onLoad:function() {
      //$('html, body').css('overflow', 'hidden'); // page scrollbars off
    }, 
    onClosed:function() {
      //$('html, body').css('overflow', ''); // page scrollbars on
      $('#colorbox').removeClass('colorbox-xl');
    },
    onComplete : function() { 
      $('#colorbox').resize(); 
    }  
  });
});

</script>