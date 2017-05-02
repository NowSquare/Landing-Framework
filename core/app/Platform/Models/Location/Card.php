<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
  protected $table = 'cards';
  protected $geofields = array('location');

  protected $casts = [
    'meta' => 'json',
    'settings' => 'json'
  ];

  public function getDates() {
    return array('created_at', 'updated_at');
  }

  public function getDateOnly() {
    return array('valid_from_date', 'expiration_date');
  }

  public function setLocationAttribute($value) {
    $this->attributes['location'] = \DB::raw("POINT($value)");
  }

  public function getLocationAttribute($value) {
    $loc =  substr($value, 6);
    $loc = preg_replace('/[ ,]+/', ',', $loc, 1);

    return substr($loc,0,-1);
  }

  public function newQuery($excludeDeleted = true) {
    $raw='';
    foreach($this->geofields as $column){
      $raw .= ' astext('.$column.') as '.$column.' ';
    }
    return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
  }

  public function scopeDistance($query, $dist, $location) {
    // Miles
    //$unit = 3959;
    // Kilometers (* 1000 = meters)
    $unit = 6371000;

    $coords = explode(',', $location);
    $lat = $coords[0];
    $lng = $coords[1];
    return $query->selectRaw("ROUND( " . $unit . " * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") ) * sin(radians(lat)) ), 0) AS distance")->havingRaw('distance < '.$dist);
  }

  public function user() {
    return $this->hasOne('App\User');
  }

  public function campaigns() {
    return $this->belongsToMany('Platform\Models\Campaigns\Campaign', 'campaign_card', 'card_id', 'campaign_id');
  }

  public function geofences() {
    return $this->belongsToMany('Platform\Models\Location\Geofence', 'geofence_card', 'card_id', 'geofence_id');
  }

  public function beacons() {
    return $this->belongsToMany('Platform\Models\Location\Beacon', 'beacon_card', 'card_id', 'beacon_id');
  }

  public function categories() {
    return $this->belongsToMany('Platform\Models\Categories\Category', 'category_card', 'card_id', 'category_id');
  }
}
