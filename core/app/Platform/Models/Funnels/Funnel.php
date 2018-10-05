<?php
namespace Platform\Models\Funnels;

use Illuminate\Database\Eloquent\Model;

class Funnel extends Model {

  protected $table = 'funnels';

  protected $casts = [
    'segment' => 'json',
    'settings' => 'json'
  ];

  public function getDates() {
    return array('created_at', 'updated_at', 'date_start', 'date_end');
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function landingSites() {
    return $this->hasMany('Modules\LandingPages\Http\Models\Site');
  }

  public function forms() {
    return $this->hasMany('Modules\Forms\Http\Models\Form');
  }

  public function emailCampaigns() {
    return $this->hasMany('Modules\EmailCampaigns\Http\Models\EmailCampaign');
  }

  public function scenarios() {
    return $this->hasMany('Modules\Scenarios\Http\Models\Scenario');
  }

  public function beacons() {
    return $this->hasMany('Modules\Beacons\Http\Models\Beacon');
  }

  public function geofences() {
    return $this->hasMany('Modules\Geofences\Http\Models\Geofence');
  }
}