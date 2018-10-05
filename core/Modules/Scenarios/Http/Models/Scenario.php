<?php
namespace Modules\Scenarios\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model {

  protected $table = 'scenarios';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function geofences() {
    return $this->belongsToMany('Modules\Geofences\Http\Models\Geofence', 'geofence_scenario', 'scenario_id', 'geofence_id');
  }

  public function beacons() {
    return $this->belongsToMany('Modules\Beacons\Http\Models\Beacon', 'beacon_scenario', 'scenario_id', 'beacon_id');
  }

  public function scenarioIf() {
    return $this->hasOne('Modules\Scenarios\Http\Models\ScenarioIf');
  }

  public function scenarioThen() {
    return $this->hasOne('Modules\Scenarios\Http\Models\ScenarioThen');
  }
}