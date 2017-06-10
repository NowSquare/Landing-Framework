<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model {
  
  protected $table = 'landing_stats';

  protected $casts = [
    'entry' => 'json',
    'meta' => 'json'
  ];

  public function setUpdatedAtAttribute($value) {
    // Do nothing.
  }

  public function getUpdatedAtColumn() {
    return null;
  }

  /**
   * Dynamically set a model's table.
   *
   * @param  $table
   * @return void
   */
  public function setTable($table) {
    $this->table = $table;
    return $this;
  }

  public function landingPage() {
    return $this->belongsTo('LandingPages\Site', 'landing_page_id');
  }
}