<div class="container">
<div class="row m-t">
  <div class="col-sm-6">
<?php
if (count($campaigns) == 0) {
?>
    <div class="card-box">
      <h1>{{ trans('global.no_data_found') }} </h1>
    </div>

<?php
} else { 
?>
    <div class="card-box" style="padding:13px">
       <div class="row">
        <div class="col-sm-10">
          <select id="campaigns" class="select2-required">
<?php
echo '<option value="">' . trans('global.all_campaigns') . '</option>';

foreach($campaigns as $key => $row) {
  $sl_campaign = \Platform\Controllers\Core\Secure::array2string(array('campaign_id' => $row['id']));
  $selected = ($row['id'] == $campaign_id) ? ' selected' : '';
  echo '<option value="' . $sl_campaign . '"' . $selected . '>' . $row['name'] . '</option>';
}
?>
          </select>
<script>
$('#campaigns').on('change', function() {
  document.location = ($(this).val() == '') ? '#/mobile/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>' : '#/mobile/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>/' + $(this).val();
});
</script>
          </div>
          <div class="col-sm-2">
             <button type="button" class="btn btn-default btn-block" onClick="$('#filter').slideToggle(150)"><i class="fa fa-filter" aria-hidden="true"></i></button>
          </div>
        </div>
    </div>
  </div>
  <div class="col-sm-6 text-center m-b-20">
      <div class="form-control" id="reportrange" style="cursor:pointer;padding:20px; width:100%; display:table"> <i class="fa fa-calendar" style="margin:0 10px 0 0"></i> <span></span> </div>
  </div>
</div>

<div class="row" id="filter" style="display: none">
  <div class="col-sm-12">
    <div class="card-box">
      <select multiple="multiple" name="places[]" id="places" class="select2-multiple-spots" data-placeholder="{{ trans('global.select_beacons_and_or_geofences') }}">
<?php
// Beacons in a group
foreach($location_groups as $location_group)
{
  $geofences_in_group = $location_group->geofences()->orderBy('name', 'asc')->get();
  $beacons_in_group = $location_group->beacons()->orderBy('name', 'asc')->get();

  echo '<optgroup label="' . $location_group->name . '">';

  foreach($geofences_in_group as $geofence)
  {
    $selected = (in_array($geofence->id, $selected_geofences)) ? ' selected' : '';
    echo '<option value="g' . $geofence->id . '" data-type="geofence"' . $selected . '>' . $geofence->name . '</option>';
  }

  foreach($beacons_in_group as $beacon)
  {
    $selected = (in_array($beacon->id, $selected_beacons)) ? ' selected' : '';
    echo '<option value="b' . $beacon->id . '" data-type="beacon"' . $selected . '>' . $beacon->name . '</option>';
  }

  echo '</optgroup>';
}
// Beacons and geofences without a group
foreach($available_geofences_wo_group as $geofence)
{
  $selected = (in_array($geofence->id, $selected_geofences)) ? ' selected' : '';
  echo '<option value="g' . $geofence->id . '" data-type="geofence"' . $selected . '>' . $geofence->name . '</option>';
}

foreach($available_beacons_wo_group as $beacon)
{
  $selected = (in_array($beacon->id, $selected_beacons)) ? ' selected' : '';
  echo '<option value="b' . $beacon->id . '" data-type="beacon"' . $selected . '>' . $beacon->name . '</option>';
}
?>
      </select>
      <button type="button" id="apply_filter" class="btn btn-primary btn-lg btn-block" style="margin-top: 10px">{{ trans('global.apply_filter') }}</button>
<script>
$('#apply_filter').on('click', function() {
  var places = JSON.stringify($('#places').val());
  var campaigns = $('#campaigns').val();
  if (campaigns == '') campaigns = 'all';
  document.location = '#/mobile/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>/' + campaigns + '/' + encodeURIComponent(places);
});
</script>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card-box">
      <div id="combine-chart">
        <div id="main_chart" class="flot-chart" style="height: 200px;">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">

    <div class="card-box">
      <div id="heatmap" style="height: 592px;">
      </div>
    </div>

  </div>
  <div class="col-sm-6">

    <div class="card-box">
      <div id="platformLegend" style="margin-bottom: 20px"></div>
      <div id="platform-donut-chart" style="height: 180px">
        <div class="flot-chart" style="height: 180px;">
        </div>
      </div>
    </div>

    <div class="card-box">
      <div id="modelLegend" style="margin-bottom: 30px; height: 100px"></div>
      <div id="model-donut-chart">
        <div class="flot-chart" style="height: 180px;">
        </div>
      </div>
    </div>

  </div>

</div>

<div class="row">
  <div class="col-sm-6">

    <div class="card-box">
      <h4 class="text-dark  header-title m-t-0 m-b-20">{{ trans('global.beacons') }}</h4>
      <div id="beaconLegend" style="margin-bottom: 30px"></div>
<?php if ($beacons[0]->name == '') echo trans('global.no_data_found'); ?>
      <div id="beacon-donut-chart" style="height: <?php echo ($beacons[0]->name == '') ? '1' : '180'; ?>px">
        <div class="flot-chart" style="height: <?php echo ($beacons[0]->name == '') ? '1' : '180'; ?>px;">
        </div>
      </div>
    </div>

  </div>
  <div class="col-sm-6">

    <div class="card-box">
      <h4 class="text-dark  header-title m-t-0 m-b-20">{{ trans('global.geofences') }}</h4>
      <div id="geofenceLegend" style="margin-bottom: 30px"></div>
<?php if ($geofences[0]->name == '') echo trans('global.no_data_found'); ?>
      <div id="geofence-donut-chart" style="height: <?php echo ($geofences[0]->name == '') ? '1' : '180'; ?>px">
        <div class="flot-chart" style="height: <?php echo ($geofences[0]->name == '') ? '1' : '180'; ?>px;">
        </div>
      </div>
    </div>

  </div>
</div>
<script>
$('#reportrange span').html(moment('<?php echo $date_start ?>').format('MMMM D, YYYY') + ' - ' + moment('<?php echo $date_end ?>').format('MMMM D, YYYY'));

$('#reportrange').daterangepicker({
  format: 'MM-DD-YYYY',
  startDate: moment('<?php echo $date_start ?>').format('MM-D-YYYY'),
  endDate: moment('<?php echo $date_end ?>').format('MM-D-YYYY'),
  minDate: moment('<?php echo $earliest_date ?>').format('MM-D-YYYY'),
  maxDate: '<?php echo date('m/d/Y') ?>',
  dateLimit: {
      days: 60
  },
  showDropdowns: true,
  showWeekNumbers: true,
  timePicker: false,
  timePickerIncrement: 1,
  timePicker12Hour: true,
  ranges: {
   '<?php echo trans('global.today') ?>': [ moment(), moment() ],
   '<?php echo trans('global.yesterday') ?>': [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
   '<?php echo trans('global.last_7_days') ?>': [ moment().subtract(6, 'days'), moment() ],
   '<?php echo trans('global.last_30_days') ?>': [ moment().subtract(29, 'days'), moment() ],
   '<?php echo trans('global.this_month') ?>': [ moment().startOf('month'), moment().endOf('month') ],
   '<?php echo trans('global.last_month') ?>': [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ]
  },

  opens: 'left',
  drops: 'down',
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-primary',
  cancelClass: 'btn-inverse',
  separator: ' {{ strtolower(trans('global.to')) }} ',
  locale: {
    applyLabel: '<?php echo trans('global.submit') ?>',
    cancelLabel: '<?php echo trans('global.reset') ?>',
    fromLabel: '<?php echo trans('global.date_from') ?>',
    toLabel: '<?php echo trans('global.date_to') ?>',
    customRangeLabel: '<?php echo trans('global.custom_range') ?>',
    daysOfWeek: ['<?php echo trans('global.su') ?>', '<?php echo trans('global.mo') ?>', '<?php echo trans('global.tu') ?>', '<?php echo trans('global.we') ?>', '<?php echo trans('global.th') ?>', '<?php echo trans('global.fr') ?>','<?php echo trans('global.sa') ?>'],
      monthNames: ['<?php echo trans('global.january') ?>', '<?php echo trans('global.february') ?>', '<?php echo trans('global.march') ?>', '<?php echo trans('global.april') ?>', '<?php echo trans('global.may') ?>', '<?php echo trans('global.june') ?>', '<?php echo trans('global.july') ?>', '<?php echo trans('global.august') ?>', '<?php echo trans('global.september') ?>', '<?php echo trans('global.october') ?>', '<?php echo trans('global.november') ?>', '<?php echo trans('global.december') ?>'],
      firstDay: 1
  }
});

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  $('#reportrange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
  var start = picker.startDate.format('YYYY-MM-DD');
  var end = picker.endDate.format('YYYY-MM-DD');

  var places = JSON.stringify($('#places').val());
  var campaigns = $('#campaigns').val();
  if (campaigns == '') campaigns = 'all';

  //var sl = '{{ $sl }}';
  //document.location = (sl == '') ? '#/mobile/analytics/' + start + '/' + end : '#/mobile/analytics/' + start + '/' + end + '/' + sl;
  document.location = '#/mobile/analytics/' + start + '/' + end + '/' + campaigns + '/' + encodeURIComponent(places);
});

//Combine graph data
var statCardViews = [
<?php foreach($main_chart_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d'] + 1 ?>)).getTime(), <?php echo $row['views'] ?>],
<?php } ?>
];

var statInteractions = [
<?php foreach($main_chart_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d']  + 1?>)).getTime(), <?php echo $row['interactions'] ?>],
<?php } ?>
];
var ticks = [
<?php foreach($main_chart_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d'] + 1 ?>)).getTime(), '<?php echo $row['m'] . '/' . $row['d'] ?>'],
<?php } ?>
];
var combinelabels = ["{{ trans('global.cards_viewed') }}", "{{ trans('global.scenarios_triggered') }}"];
var combinedatas = [statCardViews, statInteractions];

// first correct the timestamps - they are recorded as the daily
// midnights in UTC+0100, but Flot always displays dates in UTC
// so we have to add one hour to hit the midnights in the plot
for (var i = 0; i < statCardViews.length; ++i) {
  statCardViews[i][0] += 60 * 60 * 1000;
}

for (var i = 0; i < statInteractions.length; ++i) {
  statInteractions[i][0] += 60 * 60 * 1000;
}

function weekendAreas(axes) {

  var markings = [],
    d = new Date(axes.xaxis.min);

  // go to the first Saturday
  d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
  d.setUTCSeconds(0);
  d.setUTCMinutes(0);
  d.setUTCHours(0);

  var i = d.getTime();

  // when we don't set yaxis, the rectangle automatically
  // extends to infinity upwards and downwards

  do {
    markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 }, color:"#fafafa" });
    i += 7 * 24 * 60 * 60 * 1000;
  } while (i < axes.xaxis.max);

  return markings;
}

var mainOptions = {
  series : {
    shadowSize : 0,
    lines: { 
      show: true,
      fill: false,
      lineWidth: 3
    },
    points: { 
      show: true,
      fill: true,
      radius: 3
    }
  },
  grid : {
    markings: weekendAreas,
    hoverable : true,
    clickable : true,
    tickColor : "#f9f9f9",
    borderWidth : 1,
    borderColor : "hsla(0,0%,93%,.1)"
  },
  colors : ["#50b432", "#058dc7", "#ed7e17", "#af49c5"],
  tooltip : {
    show: true,
    content : function(label, date, value) {
      return "%x<br>%s: %y";
    },
    defaultTheme: false
  },
  legend : {
    position : "ne",
    margin : [0, -10],
    noColumns : 0,
    labelBoxBorderColor : null,
    labelFormatter : function(label, series) {
      // just add some space to labes
      return '' + label + '&nbsp;&nbsp;';
    },
    width : 30,
    height : 2
  },
  yaxis : {
    tickColor : '#efefef',
    tickDecimals: 0,
    font : {
      color : 'rgb(68, 68, 68)'
    }
  },
  xaxis : {
    mode: "time", 
    timeformat: "%Y-%m-%d",
    ticks: ticks,
    tickLength: 0,
    tickColor : '#f5f5f5',
    font : {
      color : 'rgb(68, 68, 68)'
    }
  }
};

var mainData = [{
  label : combinelabels[0],
  data : combinedatas[0],
  lines : {
    show : true,
    fill : false
  },
  points : {
    show : true,
    fillColor: "#50b432"
  }
}, {
  label : combinelabels[1],
  data : combinedatas[1],
  lines : {
    show : true,
    fill : false
  },
  points : {
    show : true,
    fillColor: "#058dc7"
  }
}
];

$.plot($("#combine-chart #main_chart"), mainData, mainOptions);

var platformData = [
<?php foreach($segmentation_platform as $name => $value) { ?>
{
  label : "{{ $name }}",
  data : {{ $value }}
},
<?php } ?>
];

var platformOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0,
      stroke : {
        width : 2
      }
    }
  },
  legend : {
    show : true,
    container: '#platformLegend',
    position: 'ne',
    noColumns: 3,
    labelFormatter : function(label, series) {
      return '' + label + ''
    },
    labelBoxBorderColor : null,
    margin : 10
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#50b432", "#058dc7", "#80deea", "#00b19d"],
  tooltip : {
    show: true,
    content : "%s<br>{{ trans('global.scenarios_triggered') }}: %n (%p.0%)",
    defaultTheme: false
  }
};

$.plot($("#platform-donut-chart .flot-chart"), platformData, platformOptions);

var modelData = [
<?php foreach($segmentation_model as $name => $value) { ?>
{
  label : "{{ $name }}",
  data : {{ $value }}
},
<?php } ?>
];

var modelOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0.5,
      stroke : {
        width : 2
      }
    }
  },
  legend : {
    show : true,
    container: '#modelLegend',
    position: 'ne',
    noColumns: 3,
    labelFormatter : function(label, series) {
      return '' + label + ''
    },
    labelBoxBorderColor : null,
    margin : 10
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#0D47A1", "#1565C0", "#1976D2", "#1E88E5", "#2196F3", "#42A5F5", "#64B5F6", "#90CAF9", "#BBDEFB", "#E3F2FD"],
  tooltip : {
    show: true,
    content : "%s<br>{{ trans('global.scenarios_triggered') }}: %n (%p.0%)",
    defaultTheme: false
  }
};

$.plot($("#model-donut-chart .flot-chart"), modelData, modelOptions);

var beaconData = [
<?php foreach($beacons as $beacon) { ?>
{
  label : "{{ $beacon->name }}",
  data : {{ $beacon->triggers }}
},
<?php } ?>
];

var beaconOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0.5,
      stroke : {
        width : 2
      }
    }
  },
  legend : {
    show : true,
    container: '#beaconLegend',
    position: 'ne',
    noColumns: 3,
    labelFormatter : function(label, series) {
      return '' + label + ''
    },
    labelBoxBorderColor : null,
    margin : 10
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#33691E", "#558B2F", "#689F38", "#7CB342", "#8BC34A", "#9CCC65", "#AED581", "#C5E1A5", "#DCEDC8", "#F1F8E9"],
  tooltip : {
    show: true,
    content : "%s<br>{{ trans('global.scenarios_triggered') }}: %n (%p.0%)",
    defaultTheme: false
  }
};

$.plot($("#beacon-donut-chart .flot-chart"), beaconData, beaconOptions);

var geofenceData = [
<?php foreach($geofences as $geofence) { ?>
{
  label : "{{ $geofence->name }}",
  data : {{ $geofence->triggers }}
},
<?php } ?>
];

var geofenceOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0.5,
      stroke : {
        width : 2
      }
    }
  },
  legend : {
    show : true,
    container: '#geofenceLegend',
    position: 'ne',
    noColumns: 3,
    labelFormatter : function(label, series) {
      return '' + label + ''
    },
    labelBoxBorderColor : null,
    margin : 10
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#E65100", "#EF6C00", "#F57C00", "#FB8C00", "#FF9800", "#FFA726", "#FFB74D", "#FFCC80", "#FFE0B2", "#FFF3E0"],
  tooltip : {
    show: true,
    content : "%s<br>{{ trans('global.scenarios_triggered') }}: %n (%p.0%)",
    defaultTheme: false
  }
};

$.plot($("#geofence-donut-chart .flot-chart"), geofenceData, geofenceOptions);
  
$(window).resize(function(event) {
  if ($("#combine-chart #main_chart").length) {
    $.plot($("#combine-chart #main_chart"), mainData, mainOptions);
  }

  if ($("#platform-donut-chart .flot-chart").length) {
   $.plot($("#platform-donut-chart .flot-chart"), platformData, platformOptions);
  }

  if ($("#model-donut-chart .flot-chart").length) {
   $.plot($("#model-donut-chart .flot-chart"), modelData, modelOptions);
  }

  if ($("#beacon-donut-chart .flot-chart").length) {
   $.plot($("#beacon-donut-chart .flot-chart"), beaconData, beaconOptions);
  }

  if ($("#geofence-donut-chart .flot-chart").length) {
   $.plot($("#geofence-donut-chart .flot-chart"), geofenceData, geofenceOptions);
  }
});

// Heatmap
initMap();

var map, heatmap;

function initMap() {
  map = new google.maps.Map(document.getElementById('heatmap'), {
    center: {lat: {{ env('GMAPS_DEFAULT_LAT') }}, lng: {{ env('GMAPS_DEFAULT_LNG') }}},
    zoom: {{ env('GMAPS_DEFAULT_ZOOM') }},
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