<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model {
  use NodeTrait;

  protected $table = 'landing_pages';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function site() {
    return $this->belongsTo('Modules\LandingPages\Http\Models\Site', 'landing_site_id');
  }

  /**
   * Get page url.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeUrl($query) {

    $local_domain = 'lp/' . $this->site->local_domain;

    if ($this->site->domain == '') {
      return url($local_domain);
    } else {
      return '//' . $this->site->domain;
    }
  }

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
}