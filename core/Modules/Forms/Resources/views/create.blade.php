<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/forms">{{ trans('forms::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.create_form') }}</a>
          </div>
<?php /*
          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/create" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
*/ ?>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
<?php
$i=0;
foreach ($categories as $category) {
  $i++;
?>
    <div class="col-xs-6 col-sm-4 col-lg-3">

      <div class="portlet shadow-box box-option"
        onMouseOver="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/active/' . $category['icon']) }}';"
        onMouseOut="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/color/' . $category['icon']) }}';"
      >
        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark">{{ $category['name'] }}</h3>
          <div class="clearfix"></div>
        </div>
        <div>
          <div class="text-center">
            <a href="#/forms/create/{{ $category['category'] }}">
              <img src="{{ url('assets/images/icons/color/' . $category['icon']) }}" id="box-icon{{ $i }}" class="box-icon" alt="{{ $category['name'] }}">
            </a>
          </div>
          <div class="portlet-body">
            {{ $category['desc'] }}
          </div>
          <div class="panel-footer">
            <a href="#/forms/create/{{ $category['category'] }}" class="btn btn-lg btn-primary btn-block">{{ trans('global.select') }}</a>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
</div>