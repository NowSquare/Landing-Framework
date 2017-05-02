<?php
namespace Platform\Models\Campaigns;

use Illuminate\Database\Eloquent\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class App extends Model implements StaplerableInterface {
  use EloquentTrait;

  protected $table = 'campaign_apps';

  protected $casts = [
    'settings' => 'json'
  ];

  public function __construct(array $attributes = array()) {
    $this->hasAttachedFile('photo', [
      'styles' => [
        'thumbnail' => '420x315#',
        'small' => '140x105#',
        'tiny' => '84x63#'
      ]
    ]);

    parent::__construct($attributes);
  }

  public function getDates() {
    return array('created_at', 'updated_at');
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function campaigns() {
    return $this->belongsToMany('Platform\Models\Campaigns\Campaign', 'app_campaigns', 'campaign_id', 'app_id');
  }

  public function categories() {
    return $this->belongsToMany('Platform\Models\Categories\Category', 'category_campaign_apps', 'campaign_app_id', 'category_id');
  }
}