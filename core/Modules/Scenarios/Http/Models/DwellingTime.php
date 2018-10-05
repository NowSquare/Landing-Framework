<?php
namespace Modules\Scenarios\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DwellingTime extends Model {

  protected $table='dwelling_time';

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
}