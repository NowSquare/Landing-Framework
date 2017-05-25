<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

  protected $table = 'landing_sites';

  protected $casts = [
    'meta' => 'json'
  ];

  /**
   * Conversion percentage.
   */
  public function getConversionAttribute($query) {

    if ($this->visits < $this->conversions) {
      return 100;
    } elseif ($this->conversions == 0) {
      return 0;
    } else {
      return round(($this->conversions / $this->visits) * 100);
    }
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function campaign() {
    return $this->hasOne('Platform\Models\Campaigns\Campaign');
  }

  public function pages() {
    return $this->hasMany('Modules\LandingPages\Http\Models\Page', 'landing_site_id');
  }
}