<?php
namespace Platform\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class CardStat extends Model
{

  protected $table = 'card_stats';

  protected $casts = [
    'meta' => 'json'
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function getDates() {
    return array('created_at');
  }

  public function user() {
    return $this->hasOne('App\User');
  }

  public function campaigns() {
    return $this->belongsToMany('Platform\Models\Campaigns\Campaign', 'campaign_card', 'card_id', 'campaign_id');
  }

  public function member() {
    return $this->hasOne('Platform\Models\Members\Member');
  }
}
