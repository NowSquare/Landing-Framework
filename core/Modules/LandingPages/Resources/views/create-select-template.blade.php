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
    <div class="grid-item col-xs-6 col-sm-2 col-lg-2">

      <div class="grid-item-content portlet shadow-box box-option">
        <div>
          <div class="text-center">
            <a href="#/landingpages/editor/{{ \Platform\Controllers\Core\Secure::array2string(array('landing_site_id' => 1)) }}">
              <img src="{{ $template['preview01'] }}" id="box-icon{{ $i }}" style="width:100%" alt="{{ $template['dir'] }}">
            </a>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-xs-5">
                <a href="javascript:void(0);" class="btn btn-sm btn-success btn-block" title="{{ trans('global.preview') }}" data-toggle="tooltip"><i class="mi search"></i></a>
              </div>
              <div class="col-xs-7">
                <?php /*<a href="#/landingpages/create/{{ $category }}/{{ $template['dir'] }}" class="btn btn-sm btn-primary btn-block">{{ trans('global.select') }}</a>*/ ?>
                <a href="#/landingpages/editor/{{ \Platform\Controllers\Core\Secure::array2string(array('landing_site_id' => 1)) }}" class="btn btn-sm btn-primary btn-block">{{ trans('global.select') }}</a>
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
</script>