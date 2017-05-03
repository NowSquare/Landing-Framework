<div class="container">
<?php if (\Auth::user()->free_plan && Gate::allows('limitation', 'account.plan_visible')) { ?>
  <div class="row m-t">
    <div class="col-sm-12">
    </div>
    <div class="col-md-12">
      <div class="alert alert-success">{!! trans('global.you_are_on_plan', ['plan' => '<strong>' . \Auth::user()->plan_name . '</strong>']) !!} {!! trans('global.click_here_for_more_info', ['link' => '#/plan']) !!}</div>
    </div>
  </div>
 <?php } ?>

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.create_new') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
<?php
$i=0;
foreach ($items as $item) {
  $i++;
?>
    <div class="col-xs-6 col-sm-4 col-lg-3">

      <div class="portlet shadow-box box-option"
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