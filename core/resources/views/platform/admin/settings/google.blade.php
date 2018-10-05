<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
     
       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand" href="#/admin/settings">{{ trans('global.settings') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">Google</a>
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

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Service account proximity key</h3>
        </div>
        <fieldset class="panel-body">

          <form class="ajax" id="frm" enctype="multipart/form-data" method="post" action="{{ url('platform/admin/settings/google') }}">
            {!! csrf_field() !!}

            <div class="row">
              <div class="form-group col-xs-6">
                <label for="file">{{ trans('global.json_key_file') }}</label>
                <input type="file" class="form-control" id="file" name="file" required>
              </div>
              <div class="help-block col-xs-12 m-b-20">{!! trans('global.google_proximity_key_help') !!}</div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.upload') }}</span></button>
              </div>
            </div>

          </form>

        </fieldset>
      </div>

    </div>
  </div>
</div>