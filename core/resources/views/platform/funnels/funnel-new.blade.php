<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <h1 class="text-center" style="margin-bottom:3rem">{{ trans('global.create_funnel') }}</h1>
    </div>
  </div>
 
  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/funnels/new') }}">
      {!! csrf_field() !!}
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <fieldset class="panel-body">
<?php if (count($funnels) == 0) { ?>
            <p class="lead text-center" style="margin:2rem">{{ trans('global.create_first_funnel_text') }}</p>
<?php } ?>
            <div class="form-group">
              <label for="name">{{ trans('global.name') }}</label>
              <input type="text" class="form-control input-lg" name="name" id="name" value="" placeholder="{{ trans('global.create_funnel_placeholder') }}" required autocomplete="off">
<?php if (count($funnels) == 0) { ?>
              <small class="text-muted">{{ trans('global.create_first_funnel_text2') }}</small>
<?php } ?>
            </div>

            <div class="">
<?php if (count($funnels) != 0) { ?>
              <a href="#/funnels" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
<?php } ?>
              <button class="btn btn-lg btn-success <?php if (count($funnels) == 0) { ?>btn-block<?php } ?> btn-block waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
            </div>

          </fieldset>
        </div>

    </div>
  </div>

</div>