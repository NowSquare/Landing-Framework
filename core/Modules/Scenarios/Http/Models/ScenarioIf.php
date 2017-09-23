<?php
namespace Modules\Scenarios\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ScenarioIf extends Model {

  protected $table = 'scenario_if';

  // Disabling Auto Timestamps
  public $timestamps = false;

  public function scenarios() {
    return $this->belongsToMany('Modules\Scenarios\Http\Models\Scenario', 'scenarios');
  }
}