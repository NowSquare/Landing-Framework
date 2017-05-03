<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/create">{{ trans('global.create') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.module_name') }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/create" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row flex-holder">
<?php
$i=0;
foreach ($items as $item) {
  $i++;
?>
    <div class="col-sm-4 col-lg-3 flex-item">

      <div class="portlet shadow-box box-option flex-eq-height"
        onMouseOver="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/active/' . $item['icon']) }}';"
        onMouseOut="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/color/' . $item['icon']) }}';"
      >
        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark">{{ $item['name'] }}</h3>
          <div class="clearfix"></div>
        </div>
        <div>
          <div class="text-center">
            <a href="{{ $item['url'] }}">
              <img src="{{ url('assets/images/icons/color/' . $item['icon']) }}" id="box-icon{{ $i }}" class="box-icon" alt="{{ $item['name'] }}">
            </a>
          </div>
          <div class="portlet-body">
            {{ $item['desc'] }}
          </div>
          <div class="panel-footer">
            <a href="{{ $item['url'] }}" class="btn btn-lg btn-primary btn-block">{{ trans('global.select') }}</a>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
</div>