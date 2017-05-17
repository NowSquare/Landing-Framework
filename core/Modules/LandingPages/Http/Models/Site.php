<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

  protected $table = 'landing_sites';

  protected $casts = [
    'meta' => 'json'
  ];

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