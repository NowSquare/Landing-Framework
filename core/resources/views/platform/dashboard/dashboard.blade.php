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
$i = 0;
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
  $chart_color = ($today < $yesterday) ? 'da4429' : '15cd72';

  $trend_diff = $today - $yesterday;
  $trend_diff_sign = ($trend_diff > 0) ? '+' : '';
  $trend_diff_perc = ($today > 0) ? abs(round((1 - $yesterday / $today) * 100, 0)) : 0;
  if ($today == 0 && $yesterday > 0) $trend_diff_perc = 100;

  if ($today == $yesterday) {
    $trend_icon = 'trending_flat';
    $trend_color = 'primary';
    $trend_diff = 0;
    $chart_color = '138dfa';
  }

  $chart_color = '138dfa';
  $percent = ($site->conversion == '') ? 0 : $site->conversion;
?>
    <div class="col-sm-6 col-lg-3">
      <div class="card-box widget-icon card-box-link" style="border:0">
        <a href="#/platform#/landingpages/analytics/{{ $sl_page }}">

          <div id="circliful-lp-{{ $i }}" style="width: 80px; float:left; top: -16px; left: -8px; position: relative"></div>

          <script>
          $('#circliful-lp-{{ $i }}').circliful({
            percent: {{ $percent }},
            icon: 'e152',
            iconSize: 50,
            iconPosition: 'bottom',
            fgcolor: '#{{ $chart_color }}',
            bgcolor: '#ebeff2',
            showPercent: 0,
            textAdditionalCss: 'display:none'
          });
          </script>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ $site->name }}</p>
            <h4 class="m-t-0 m-b-5 text-{{ $trend_color }}"><i class="mi {{ $trend_icon }}" style="font-size: 14px; position: relative; top: 3px"></i> &nbsp; {{ $trend_diff_sign }}<span class="counter">{{ $trend_diff }}</span> (<span class="counter">{{ $trend_diff_perc }}</span>%)</h4>
          </div>
        </a>
      </div>
    </div>
<?php
  $i++;
}
?>
  </div>

<?php
} // count($sites) > 0
?>

<?php if (count($forms) > 0) { ?>

  <div class="row<?php if (count($sites) == 0) { ?> m-t<?php } ?>">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.module_name_plural') }} ({{ count($forms) }})</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
<?php
$i = 0;
foreach($forms as $form) {
  $sl_form = \Platform\Controllers\Core\Secure::array2string(['form_id' => $form->id]);

  // Entries
  $today = \Modules\Forms\Http\Models\Stat::where('form_id', $form->id)->where(\DB::raw('DATE(created_at)'), \Carbon\Carbon::now(\Auth::user()->timezone)->tz('UTC')->format('Y-m-d'))->count();
  $yesterday = \Modules\Forms\Http\Models\Stat::where('form_id', $form->id)->where(\DB::raw('DATE(created_at)'), \Carbon\Carbon::yesterday(\Auth::user()->timezone)->tz('UTC')->format('Y-m-d'))->count();

  $trend_icon = ($today < $yesterday) ? 'trending_down' : 'trending_up';
  $trend_color = ($today < $yesterday) ? 'danger' : 'success';
  $chart_color = ($today < $yesterday) ? 'da4429' : '15cd72';

  $trend_diff = $today - $yesterday;
  $trend_diff_sign = ($trend_diff > 0) ? '+' : '';
  $trend_diff_perc = ($today > 0) ? abs(round((1 - $yesterday / $today) * 100, 0)) : 0;
  if ($today == 0 && $yesterday > 0) $trend_diff_perc = 100;

  if ($today == $yesterday) {
    $trend_icon = 'trending_flat';
    $trend_color = 'primary';
    $trend_diff = 0;
    $chart_color = '138dfa';
  }

  $chart_color = '138dfa';
  $percent = ($form->conversion == '') ? 0 : $form->conversion;
?>
    <div class="col-sm-6 col-lg-3">
      <div class="card-box widget-icon card-box-link" style="border:0">
        <a href="#/platform#/forms/entries/{{ $sl_form }}">

          <div id="circliful-f-{{ $i }}" style="width: 80px; float:left; top: -16px; left: -8px; position: relative"></div>

          <script>
          $('#circliful-f-{{ $i }}').circliful({
            percent: {{ $percent }},
            icon: 'e152',
            iconSize: 50,
            iconPosition: 'bottom',
            fgcolor: '#{{ $chart_color }}',
            bgcolor: '#ebeff2',
            showPercent: 0,
            textAdditionalCss: 'display:none'
          });
          </script>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ $form->name }}</p>
            <h4 class="m-t-0 m-b-5 text-{{ $trend_color }}"><i class="mi {{ $trend_icon }}" style="font-size: 14px; position: relative; top: 3px"></i> &nbsp; {{ $trend_diff_sign }}<span class="counter">{{ $trend_diff }}</span> (<span class="counter">{{ $trend_diff_perc }}</span>%)</h4>
          </div>
        </a>
      </div>
    </div>
<?php
  $i++;
}
?>
  </div>
<?php
} // count($forms) > 0
?>
  <style type="text/css">
  svg .icon {
    font-family: Material Icons;
  }
  .card-box-link {
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
  transition: box-shadow 0.2s ease-in-out;
  }
  .card-box-link:hover {
    box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12), 0 3px 5px -1px rgba(0, 0, 0, 0.2);
  }
  </style>
<?php if (count($sites) == 0 && count($forms) == 0) { ?>
  <div class="row<?php if (count($sites) == 0 && count($forms) == 0) { ?> m-t<?php } ?>">
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
<?php } ?>
</div>