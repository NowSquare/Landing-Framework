<?php
namespace Modules\Modals\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class Modal extends Model implements StaplerableInterface {
  use EloquentTrait;

  protected $table = 'modals';

  protected $casts = [
    'active_week_days' => 'json',
    'hosts' => 'json',
    'paths' => 'json',
    'referrer_hosts' => 'json',
    'referrer_paths' => 'json',
    'settings' => 'json',
    'meta' => 'json'
  ];

  public function getDates() {
    return array('created_at', 'updated_at', 'active_start', 'active_end');
  }

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  public function __construct(array $attributes = array()) {
    /*
    $this->hasAttachedFile('photo', [
      'styles' => [
        'large' => '800x800',
        'small' => '128x128#'
      ]
    ]);
    */

    parent::__construct($attributes);
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }

  /**
   * Get modal url.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeUrl($query) {

    $local_domain = 'modal/' . $this->local_domain;

    if ($this->domain == '') {
      return url($local_domain);
    } else {
      return '//' . $this->domain;
    }
  }

}