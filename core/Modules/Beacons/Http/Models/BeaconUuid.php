<?php
namespace Modules\Beacons\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BeaconUuid extends Model {
  protected $table = 'beacon_uuids';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function user() {
    return $this->belongsTo('App\User');
  }
}