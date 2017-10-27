<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.settings') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 col-lg-2">

      @include("platform.admin.settings.menu")

    </div>

    <div class="col-md-9 col-lg-10">
      <div class="panel panel-inverse panel-border">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
          <p class="">{!! trans('global.settings_desc') !!}</p>
        </div>
      </div>
    </div>


  </div>
</div>