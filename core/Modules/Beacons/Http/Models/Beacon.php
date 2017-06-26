<?php
namespace Modules\Beacons\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model {

  protected $table = 'beacons';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }

}