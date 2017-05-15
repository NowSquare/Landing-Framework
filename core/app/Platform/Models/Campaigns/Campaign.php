<?php
namespace Platform\Models\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model {

  protected $table = 'campaigns';

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
}