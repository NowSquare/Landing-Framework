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

<?php if (count($active_modules) > 0) { ?>

<?php if (count($sites) > 0) { ?>

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('landingpages::global.module_name_plural') }} ({{ count($sites) }})</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
<?php
foreach($sites as $site) {
  $page = $site->pages->first();
  $page_id = $page->id;
  $sl_site = \Platform\Controllers\Core\Secure::array2string(['landing_site_id' => $site->id]);
  $sl_page = \Platform\Controllers\Core\Secure::array2string(['landing_page_id' => $page_id]);

  // Visits
  $today = \Modules\LandingPages\Http\Models\Stat::where('landing_page_id', $page_id)->where(\DB::raw('DATE(created_at)'), \Carbon\Carbon::now(\Auth::user()->timezone)->tz('UTC')->format('Y-m-d'))->count();
  $yesterday = \Modules\LandingPages\Http\Models\Stat::where('landing_page_id', $page_id)->where(\DB::raw('DATE(created_at)'), \Carbon\Carbon::yesterday(\Auth::user()->timezone)->tz('UTC')->format('Y-m-d'))->count();

  $trend_icon = ($today < $yesterday) ? 'trending_down' : 'trending_up';
  $trend_color = ($today < $yesterday) ? 'danger' : 'success';
  $trend_diff = ($today > 0) ? $today - $yesterday : 0;
  $trend_diff_perc = ($today > 0) ? round((1 - $yesterday / $today) * 100, 0) : 0;
  if ($today == 0 && $yesterday > 0) $trend_diff_perc = -100;

  if ($today == $yesterday) {
    $trend_icon = 'trending_flat';
    $trend_color = 'primary';
    $trend_diff = 0;
  }

?>
    <div class="col-sm-6 col-lg-3">
      <div class="card-box widget-icon">
        <a href="#/platform#/landingpages/analytics/{{ $sl_page }}">
          <img src="{{ url('assets/images/icons/color/onepagelayout.svg') }}" alt="{{ $site->name }}" style="width:60px; float:left; top: -6px; position: relative;">
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ $site->name }}</p>
            <h4 class="m-t-0 m-b-5 text-{{ $trend_color }}"><i class="mi {{ $trend_icon }}" style="font-size: 14px; position: relative; top: 3px"></i> &nbsp; <span class="counter">{{ $trend_diff }}</span> (<span class="counter">{{ $trend_diff_perc }}</span>%)</h4>
          </div>
        </a>
      </div>
    </div>
<?php
}
?>
  </div>
<?php
} // count($sites) > 0
?>
  <div class="row<?php if (count($sites) == 0) { ?> m-t<?php } ?>">
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
foreach ($active_modules as $module) {
  $i++;
?>
    <div class="col-xs-6 col-sm-4 col-lg-3">

      <div class="portlet shadow-box box-option"
        onMouseOver="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/active/' . $module['icon']) }}';"
        onMouseOut="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/color/' . $module['icon']) }}';"
      >
        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark">{{ $module['name'] }}</h3>
          <div class="clearfix"></div>
        </div>
        <div>
          <div class="text-center">
            <a href="{{ $module['url'] }}">
              <img src="{{ url('assets/images/icons/color/' . $module['icon']) }}" id="box-icon{{ $i }}" class="box-icon" alt="{{ $module['name'] }}">
            </a>
          </div>
          <div class="portlet-body">
            {{ $module['desc'] }}
          </div>
          <div class="panel-footer">
            <a href="{{ $module['url'] }}" class="btn btn-lg btn-primary btn-block">{{ trans('global.select') }}</a>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
<?php } ?>
</div>