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
            <a class="navbar-brand link" href="#/scenarios">{{ trans('scenarios::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.analytics') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ $funnel->name }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right m-l-15">
              <div class="form-control" id="reportrange" style="cursor:pointer;padding:5px 10px; display:table">
                <i class="mi date_range" style="margin:0 5px 0 0"></i> <span></span>
              </div>
            </div>

          </div>
        </div>
      </nav>
     
    </div>
  </div>

<?php
if (! $data_found) {
?>
<div class="panel panel-border panel-inverse">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('scenarios::global.no_data_found') }}</h3>
  </div>
  <div class="panel-body">
    <p></p>
  </div>
</div>

<?php
} else { 

?>
<div class="row" id="filter" style="display: none">
  <div class="col-sm-12">
    <div class="card-box">
      <select multiple="multiple" name="places[]" id="places" class="select2-multiple-spots" data-placeholder="{{ trans('scenarios::global.select_beacons_and_or_geofences') }}">
<?php
// Beacons and geofences
foreach($geofences as $geofence) {
  $selected = (in_array($geofence->id, $selected_geofences)) ? ' selected' : '';
  echo '<option value="g' . $geofence->id . '" data-type="geofence"' . $selected . '>' . $geofence->name . '</option>';
}

foreach($beacons as $beacon) {
  $selected = (in_array($beacon->id, $selected_beacons)) ? ' selected' : '';
  echo '<option value="b' . $beacon->id . '" data-type="beacon"' . $selected . '>' . $beacon->name . '</option>';
}
?>
      </select>
      <button type="button" id="apply_filter" class="btn btn-primary btn-lg btn-block" style="margin-top: 10px">{{ trans('scenarios::global.apply_filter') }}</button>
<script>
$('#apply_filter').on('click', function() {
  var places = JSON.stringify($('#places').val());
  document.location = '#/mobile/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>/' + encodeURIComponent(places);
});
</script>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card-box">
      <div id="stats_line_chart">

        <div style="height:244px">
        </div>

      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">

    <div class="card-box">
      <div id="platform_pie">
        <div style="height: 220px;">
        </div>
      </div>
    </div>

  </div>
  <div class="col-sm-6">

    <div class="card-box">
      <div id="model_pie">
        <div style="height: 220px;">
        </div>
      </div>
    </div>

  </div>

</div>

<div class="row">
  <div class="col-sm-6">

    <div class="card-box">
      <div id="beacon_pie">
        <div style="height: 220px;">
        </div>
      </div>
    </div>

  </div>
  <div class="col-sm-6">

    <div class="card-box">
      <div id="geofence_pie">
        <div style="height: 220px;">
        </div>
      </div>
    </div>

  </div>

</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card-box" style="padding:0"><?php /*
      <h4 class="text-dark  header-title" style="margin:20px;">{{ trans('scenarios::global.heatmap') }}</h4>*/ ?>
      <div id="heatmap" style="height: 592px;">
      </div>
    </div>
  </div>
</div>

<script>

/* 
 * Google Charts
 */

google.charts.setOnLoadCallback(drawChart);

var chartRangeInstance, 
    chartRangeData, 
    chartRangeOpts,
    platformChart,
    platformData,
    platformOptions,
    modelChart,
    modelData,
    modelOptions,
    beaconChart,
    beaconData,
    beaconOptions,
    geofenceChart,
    geofenceData,
    geofenceOptions;

 function floorDate(datetime) {
   var newDate = new Date(datetime);
   newDate.setHours(0);
   newDate.setMinutes(0);
   newDate.setSeconds(0);
   return newDate;
 }

function drawChart() {

  /* 
   * Interactions
   */
  var jsonData = <?php echo json_encode($chartJson); ?>;

  chartRangeData = new google.visualization.DataTable(jsonData);

  // load custom ticks
  var ticks = [];
  for (var i = 0; i < chartRangeData.getNumberOfRows(); i++) {
    ticks.push(chartRangeData.getValue(i, 0));
  }

  chartRangeOpts = {
    width: '100%',
    height: 220,
    legend: {position: 'none'},
    pointSize: 4,
    axes: {
      x: {
        0: {side: 'top'}
      }
    },
    vAxis: {
      minValue: 0,
      viewWindow: {min: -0.15, max: parseInt(jsonData.vars.max) + 1},
      format: '0',
    },
    hAxis: {
      gridlines: {count: 30},
      ticks: ticks,
      format: 'M/d/yy'
    }
  };

  chartRangeInstance = new google.charts.Line(document.getElementById('stats_line_chart'));
  chartRangeInstance.draw(chartRangeData, google.charts.Line.convertOptions(chartRangeOpts));

  /* 
   * Platforms
   */

  platformData = google.visualization.arrayToDataTable([
    ["{{ str_replace('"', '\"', trans('scenarios::global.platforms')) }}", "{{ str_replace('"', '\"', trans('scenarios::global.interactions')) }}"],
<?php
  foreach ($segmentation_platform as $key => $value) {
    echo '["' . str_replace('"', '\"', $key) . '", ' . $value . '],';
  }
?>
  ]);

  platformOptions = {
    title: "{{ str_replace('"', '\"', trans('scenarios::global.platforms')) }}"
  };

  platformChart = new google.visualization.PieChart(document.getElementById('platform_pie'));

  platformChart.draw(platformData, platformOptions);

  /* 
   * Models
   */

  modelData = google.visualization.arrayToDataTable([
    ["{{ str_replace('"', '\"', trans('scenarios::global.models')) }}", "{{ str_replace('"', '\"', trans('scenarios::global.interactions')) }}"],
<?php
  foreach ($segmentation_model as $key => $value) {
    echo '["' . str_replace('"', '\"', $key) . '", ' . $value . '],';
  }
?>
  ]);

  modelOptions = {
    title: "{{ str_replace('"', '\"', trans('scenarios::global.models')) }}"
  };

  modelChart = new google.visualization.PieChart(document.getElementById('model_pie'));

  modelChart.draw(modelData, modelOptions);

  /* 
   * Beacons
   */

  beaconData = google.visualization.arrayToDataTable([
    ["{{ str_replace('"', '\"', trans('scenarios::global.beacons')) }}", "{{ str_replace('"', '\"', trans('scenarios::global.interactions')) }}"],
<?php
  foreach ($beacons as $beacon) {
    echo '["' . str_replace('"', '\"', $beacon->name) . '", ' . $beacon->triggers . '],';
  }
?>
  ]);

  beaconOptions = {
    title: "{{ str_replace('"', '\"', trans('scenarios::global.beacons')) }}"
  };

  beaconChart = new google.visualization.PieChart(document.getElementById('beacon_pie'));

  beaconChart.draw(beaconData, beaconOptions);

  /* 
   * Geofences
   */

  geofenceData = google.visualization.arrayToDataTable([
    ["{{ str_replace('"', '\"', trans('scenarios::global.geofences')) }}", "{{ str_replace('"', '\"', trans('scenarios::global.interactions')) }}"],
<?php
  foreach ($geofences as $geofence) {
    echo '["' . str_replace('"', '\"', $geofence->name) . '", ' . $geofence->triggers . '],';
  }
?>
  ]);

  geofenceOptions = {
    title: "{{ str_replace('"', '\"', trans('scenarios::global.geofences')) }}"
  };

  geofenceChart = new google.visualization.PieChart(document.getElementById('geofence_pie'));

  geofenceChart.draw(geofenceData, geofenceOptions);
}

function redrawChart() {
  chartRangeInstance.draw(chartRangeData, google.charts.Line.convertOptions(chartRangeOpts));
  platformChart.draw(platformData, platformOptions);
  modelChart.draw(modelData, modelOptions);
  beaconChart.draw(beaconData, beaconOptions);
  geofenceChart.draw(geofenceData, geofenceOptions);
}

$(window).resize($.debounce(100, redrawChart));


// Heatmap
initMap();

var map, heatmap;

function initMap() {
  map = new google.maps.Map(document.getElementById('heatmap'), {
    center: {lat: {{ env('GMAPS_DEFAULT_LAT') }}, lng: {{ env('GMAPS_DEFAULT_LNG') }}},
    zoom: {{ env('GMAPS_DEFAULT_ZOOM') }},
    scrollwheel: false,
    mapTypeId: 'roadmap'
  });

<?php if (count($heatmap) > 0) { ?>
  // Bounding box
  var bounds = new google.maps.LatLngBounds();
  getPoints().forEach(function(point) {
    bounds.extend({'lat': point.location.lat(), 'lng': point.location.lng()});
  });

  map.fitBounds(bounds);

  // Calculate zoom
  var mapDim = { height: $('#heatmap').height(), width: $('#heatmap').width() };
  map.setZoom(getBoundsZoomLevel(bounds, mapDim));

  heatmap = new google.maps.visualization.HeatmapLayer({
    data: getPoints(),
    map: map
  });

  heatmap.set('radius', 10);
  heatmap.set('opacity', 0.6);
<?php } ?>
}

function toggleHeatmap() {
  heatmap.setMap(heatmap.getMap() ? null : map);
}

function changeGradient() {
  var gradient = [
    'rgba(0, 255, 255, 0)',
    'rgba(0, 255, 255, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(0, 127, 255, 1)',
    'rgba(0, 63, 255, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(0, 0, 223, 1)',
    'rgba(0, 0, 191, 1)',
    'rgba(0, 0, 159, 1)',
    'rgba(0, 0, 127, 1)',
    'rgba(63, 0, 91, 1)',
    'rgba(127, 0, 63, 1)',
    'rgba(191, 0, 31, 1)',
    'rgba(255, 0, 0, 1)'
  ]
  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
}

function getPoints() {
  return [
<?php foreach($heatmap as $location) { ?>
    {location: new google.maps.LatLng({{ $location['lat'] }} , {{ $location['lng'] }}), weight: {{ $location['weight'] }}},
<?php } ?>
  ];
}

function getBoundsZoomLevel(bounds, mapDim) {
  var WORLD_DIM = { height: 256, width: 256 };
  var ZOOM_MAX = 21;

  function latRad(lat) {
      var sin = Math.sin(lat * Math.PI / 180);
      var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
      return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
  }

  function zoom(mapPx, worldPx, fraction) {
      return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
  }

  var ne = bounds.getNorthEast();
  var sw = bounds.getSouthWest();

  var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

  var lngDiff = ne.lng() - sw.lng();
  var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

  var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
  var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

  return Math.min(latZoom, lngZoom, ZOOM_MAX);
}
</script>
<?php } ?>
<script>
/* 
 * Date Range Picker
 */

$('#reportrange span').html(moment('<?php echo $date_start ?>').format('MMMM D, YYYY') + ' - ' + moment('<?php echo $date_end ?>').format('MMMM D, YYYY'));

daterangepicker_opts.ranges = {
 [ _lang['today'] ]: [ moment(), moment() ],
 [ _lang['yesterday'] ]: [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
 [ _lang['last_7_days'] ]: [ moment().subtract(6, 'days'), moment() ],
 [ _lang['last_30_days'] ]: [ moment().subtract(29, 'days'), moment() ],
 [ _lang['this_month'] ]: [ moment().startOf('month'), moment().endOf('month') ],
 [ _lang['last_month'] ]: [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ]
};
  
daterangepicker_opts.startDate = moment('<?php echo $date_start ?>').format('MM-D-YYYY');
daterangepicker_opts.endDate = moment('<?php echo $date_end ?>').format('MM-D-YYYY');
daterangepicker_opts.minDate = moment('<?php echo $earliest_date ?>').format('MM-D-YYYY');
daterangepicker_opts.maxDate = '<?php echo date('m/d/Y') ?>';

$('#reportrange').daterangepicker(daterangepicker_opts, 
  function(start, end) {
    $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
    startDate = start;
    endDate = end;
});

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  $('#reportrange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
  var start = picker.startDate.format('YYYY-MM-DD');
  var end = picker.endDate.format('YYYY-MM-DD');

  var places = JSON.stringify($('#places').val());

  document.location = '#/scenarios/analytics/' + start + '/' + end + '/' + encodeURIComponent(places);
});

</script>