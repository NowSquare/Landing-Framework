<?php
namespace Modules\EmailCampaigns\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

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

  public function email() {
    return $this->belongsTo('Models\Emails', 'email_id');
  }
}