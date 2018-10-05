<?php
namespace Modules\EmailCampaigns\Http\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model {

  protected $table = 'email_campaigns';

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

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }

  public function emails() {
    return $this->hasMany('Modules\EmailCampaigns\Http\Models\Email', 'email_campaign_id');
  }
}