<?php
namespace Platform\Models\Campaigns;

use Illuminate\Database\Eloquent\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class Campaign extends Model implements StaplerableInterface {
  use EloquentTrait;

  protected $table = 'campaigns';
  protected $geofields = array('location');

  protected $casts = [
    'segment' => 'json',
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

  public function setLocationAttribute($value) {
    $this->attributes['location'] = \DB::raw("POINT($value)");
  }

  public function getLocationAttribute($value) {
    $loc =  substr($value, 6);
    $loc = preg_replace('/[ ,]+/', ',', $loc, 1);

    return substr($loc,0,-1);
  }

  public function newQuery($excludeDeleted = true) {
    $raw='';
    foreach($this->geofields as $column){
      $raw .= ' astext('.$column.') as '.$column.' ';
    }
    return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
  }

  public function scopeDistance($query, $dist, $location) {
    return $query->whereRaw('st_distance(location, POINT('.$location.')) < '.$dist);
  }

  public function getDates() {
    return array('created_at', 'updated_at', 'date_start', 'date_end');
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function scenarios() {
    return $this->hasMany('Platform\Models\Location\Scenario');
  }

  public function cards() {
    return $this->belongsToMany('Platform\Models\Location\Card', 'campaign_card', 'campaign_id', 'card_id');
  }

  public function cardStat() {
    return $this->hasMany('Platform\Models\Analytics\CardStat', 'card_stats', 'campaign_id', 'card_id');
  }

  public function apps() {
    return $this->belongsToMany('Platform\Models\Campaigns\App', 'app_campaigns', 'campaign_id', 'app_id');
  }

  public function categories() {
    return $this->belongsToMany('Platform\Models\Categories\Category', 'category_campaign', 'campaign_id', 'category_id');
  }
}