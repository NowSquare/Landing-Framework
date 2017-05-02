<?php
namespace Platform\Models\Core;

use Illuminate\Database\Eloquent\Model;

Class Setting extends Model
{
  protected $table = 'settings';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function users()
  {
    return $this->hasOne('App\User');
  }
}