<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class LocationGroup extends Model {

  protected $table = 'location_groups';

	// Disabling Auto Timestamps
  public $timestamps = false;

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function geofences() {
    return $this->hasMany('Platform\Models\Location\Geofence');
  }

  public function beacons() {
    return $this->hasMany('Platform\Models\Location\Beacon');
  }
}