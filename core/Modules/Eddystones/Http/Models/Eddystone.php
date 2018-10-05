<?php
namespace Modules\Eddystones\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Eddystone extends Model {

  protected $table = 'eddystones';

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