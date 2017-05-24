<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model {

  protected $casts = [
    'entry' => 'json',
    'meta' => 'json'
  ];

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