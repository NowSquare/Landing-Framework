<?php
namespace Modules\EmailCampaigns\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Email extends Model {
  use NodeTrait;

  protected $table = 'emails';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function emailCampaign() {
    return $this->belongsTo('Modules\EmailCampaigns\Http\Models\EmailCampaign', 'email_id');
  }

  /**
   * Get page url.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeUrl($query) {

    $local_domain = 'ec/' . $this->local_domain;

    if ($this->domain == '') {
      return url($local_domain);
    } else {
      return '//' . $this->domain;
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