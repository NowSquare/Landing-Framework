<?php
namespace Modules\Scenarios\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model {

  protected $table='interactions';
  protected $geofields = array('location');

  protected $casts = [
    'segment' => 'json',
    'extra' => 'json'
  ];

  public function setUpdatedAtAttribute($value) {
    // Do nothing.
  }

  public function getUpdatedAtColumn() {
    return null;
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }
}