<?php
namespace Modules\Properties\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {

  protected $table = 'properties';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }

  public function city() {
    return $this->belongsTo('City');
  }

  public function county() {
    return $this->belongsTo('County');
  }

  public function property_type() {
    return $this->belongsTo('PropertyType');
  }

  public function sales_type() {
    return $this->belongsTo('SalesType');
  }

  public function features() {
    return $this->belongsToMany('PropertyFeature', 'property_feature', 'property_feature_id', 'property_id');
  }

  public function surrounding() {
    return $this->belongsToMany('PropertySurrounding', 'property_surrounding', 'property_surrounding_id', 'property_id');
  }

  public function garages() {
    return $this->belongsToMany('PropertyGarage', 'property_garage', 'property_garage_id', 'property_id');
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
}