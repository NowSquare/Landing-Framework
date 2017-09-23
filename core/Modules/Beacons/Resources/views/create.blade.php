<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/beacons">{{ trans('beacons::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('beacons::global.create_beacon') }}</a>
          </div>
<?php if (! $first) { ?>
          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/beacons" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
<?php } ?>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">

    <form class="ajax" id="frm" method="post" action="{{ url('beacons/beacon') }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
        <div class="panel panel-default">
          <fieldset class="panel-body">
           
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>
          
            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>

          </fieldset>
        </div>
        
        <div class="panel panel-default">

          <fieldset class="panel-body">

            <div class="form-group">
<?php
echo Former::select()
  ->name('uuid')
  ->options($uuids)
  ->required(true)
  ->class('select2-datalist form-control')
  ->dataPost(url('beacons/beacon-uuid'))
  ->dataToken(csrf_token())
  ->dataTitle(trans('beacons::global.enter_uuid'))
  ->label(trans('beacons::global.uuid') . ' <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . trans('beacons::global.uuid_help') . '">&#xE887;</i>');
?>
            </div>

            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                  <label for="major">{{ trans('beacons::global.major') }} <sup>*</sup></label>
                  <input type="number" class="form-control" name="major" id="major" required value="0">
                </div>
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                  <label for="minor">{{ trans('beacons::global.minor') }} <sup>*</sup></label>
                  <input type="number" class="form-control" name="minor" id="minor" required value="0">
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('beacons::global.location') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('beacons::global.beacon_location_help') }}">&#xE887;</i></h3>
          </div>
          <fieldset>
            <input id="pac-input" class="gcontrols" type="text" placeholder="{{ trans('global.search_') }}" style="display: none">
            <div id="gmap" style="width: 100%; height: 312px;"></div>
            <input type="hidden" id="lat" name="lat" value="{{ env('GMAPS_DEFAULT_LAT') }}">
            <input type="hidden" id="lng" name="lng" value="{{ env('GMAPS_DEFAULT_LNG') }}">
            <input type="hidden" id="zoom" name="zoom" value="{{ env('GMAPS_DEFAULT_ZOOM') }}">
          </fieldset>
        </div>
      </div>
      <!-- end col -->

      <div class="col-md-12">
   
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
<?php if (! $first) { ?>
            <a href="#/beacons" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
<?php } ?>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
    
      </div>

    </form>

  </div>
</div>

<script>
initMap();

// Catch enter
$('#pac-input').on('keypress', function(e) {
  if (e.keyCode == 13) {
    return false;
  }
});

function initMap() {
  var map = new google.maps.Map(document.getElementById('gmap'), {
    center: {lat: {{ env('GMAPS_DEFAULT_LAT') }}, lng: {{ env('GMAPS_DEFAULT_LNG') }}},
    zoom: {{ env('GMAPS_DEFAULT_ZOOM') }},
    mapTypeId: 'roadmap'
  });

  var marker = new google.maps.Marker({
    map: map,
    draggable:true,
    animation: google.maps.Animation.DROP,
    position: {lat: {{ env('GMAPS_DEFAULT_LAT') }}, lng: {{ env('GMAPS_DEFAULT_LNG') }}},
  });

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      map.setCenter(pos);
      map.setZoom(16);
      marker.setPosition(pos);
      
      $('#zoom').val(map.getZoom());
      setLngLat(marker);
    }, function() {
      // User doesn't allow geolocation, do nothing
    });
  } else {
    // Browser doesn't support Geolocation, do nothing
  }

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  setTimeout(function() {
    $('#pac-input').fadeIn();
  }, 500);
  
  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Update marker
      marker.setPosition(place.geometry.location);
      marker.setIcon(icon);

      // Create a marker for each place.
      /*
      markers.push(new google.maps.Marker({
        map: map,
        draggable:true,
        animation: google.maps.Animation.DROP,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));
*/
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);

    $('#zoom').val(map.getZoom());
    setLngLat(marker);
  });

  google.maps.event.addListener(marker, 'dragend', function() 
  {
    setLngLat(marker);
  });

  google.maps.event.addListener(map, 'zoom_changed', function(event) {
    $('#zoom').val(map.getZoom());
  });  

  google.maps.event.addListener(map, 'click', function(event) {
    marker.setPosition(event.latLng);
    setLngLat(marker);
  });  
}

function setLngLat(marker){
  $('#lat').val(marker.getPosition().lat());
  $('#lng').val(marker.getPosition().lng());
}
</script>