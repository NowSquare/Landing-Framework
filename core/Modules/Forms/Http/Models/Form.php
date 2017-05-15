<?php
namespace Modules\Forms\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model {

  protected $table = 'forms';

  protected $casts = [
    'meta' => 'json',
    'meta_published' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function campaign() {
    return $this->hasOne('Platform\Models\Campaigns\Campaign');
  }
}