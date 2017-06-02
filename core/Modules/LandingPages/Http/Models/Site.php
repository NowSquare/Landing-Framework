<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;
use \Platform\Controllers\Core;

class Site extends Model {

  protected $table = 'landing_sites';

  /**
   * The "booting" method of the model.
   *
   * We'll use this method to register event listeners.
   */
  protected static function boot()
  {
    parent::boot();

    //static::created(function ($model) {
      //dd($model);
    //});

    // Make sure to pull when deleting:
    // NOT: Models\Site::where('id', $site_id)->delete();
    // BUT: Models\Site::where('id', $site_id)->first()->delete();
    static::deleting(function ($model) {

      // Delete records
      \DB::table('x_landing_stats_' . $model->user_id)->where('landing_site_id', $model->id)->delete();

      // Delete files
      $storage_root = 'landingpages/site/' . Core\Secure::staticHash($model->user_id) . '/' . Core\Secure::staticHash($model->id, true);
      \Storage::disk('public')->deleteDirectory($storage_root);
    });
  }

  protected $casts = [
    'meta' => 'json'
  ];

  /**
   * Conversion percentage.
   */
  public function getConversionAttribute($query) {

    if ($this->visits < $this->conversions) {
      return 100;
    } elseif ($this->conversions == 0) {
      return 0;
    } else {
      return round(($this->conversions / $this->visits) * 100);
    }
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->hasOne('Platform\Models\Funnels\Funnel');
  }

  public function pages() {
    return $this->hasMany('Modules\LandingPages\Http\Models\Page', 'landing_site_id');
  }
}