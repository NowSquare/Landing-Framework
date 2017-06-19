<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'plans';

  protected $casts = [
    'limitations' => 'json',
    'extra' => 'json'
  ];

  public function getLimitationsAttribute($key) {
    $key = json_decode($key, true);

    // Get all modules
    $modules = \Module::enabled();

    if ($this->id == 1) {
      $key['account']['plan_visible'] = 1;
      $key['media']['visible'] = 1;

      // Allow all modules
      foreach ($modules as $module) {
        $namespace = $module->getLowerName();
        $enabled = config($namespace . '.enabled');
        $in_plan = config($namespace . '.in_plan');
        $in_plan_amount = config($namespace . '.in_plan_amount');
        $in_plan_default_amount = config($namespace . '.in_plan_default_amount');
        $extra_plan_config_boolean = config($namespace . '.extra_plan_config_boolean');
        $extra_plan_config_string = config($namespace . '.extra_plan_config_string');

        if ($enabled && $in_plan) {
          $key[$namespace]['visible'] = 1;
          if ($in_plan_amount) $key[$namespace]['max'] = $in_plan_default_amount;
          if ($extra_plan_config_boolean && ! empty($extra_plan_config_boolean)) {
            foreach($extra_plan_config_boolean as $config => $value) {
              $key[$namespace][$config] = 1;
            }
          }
          if ($extra_plan_config_string && ! empty($extra_plan_config_string)) {
            foreach($extra_plan_config_string as $config => $value) {
              $key[$namespace][$config] = $value;
            }
          }
        }
      }
    } else {
      // Default values
      if (! isset($key['account']['plan_visible'])) $key['account']['plan_visible'] = 1;
      if (! isset($key['media']['visible'])) $key['media']['visible'] = 0;

      // Disallow all modules
      foreach ($modules as $module) {
        $namespace = $module->getLowerName();
        $enabled = config($namespace . '.enabled');
        $in_plan = config($namespace . '.in_plan');
        $in_free_plan = config($namespace . '.in_free_plan');
        $in_free_plan_default_amount = config($namespace . '.in_free_plan_default_amount');
        $in_plan_amount = config($namespace . '.in_plan_amount');
        $in_plan_default_amount = config($namespace . '.in_plan_default_amount');
        $extra_plan_config_boolean = config($namespace . '.extra_plan_config_boolean');
        $extra_plan_config_string = config($namespace . '.extra_plan_config_string');

        if ($enabled && $in_plan) {
          if (! isset($key[$namespace]['visible'])) $key[$namespace]['visible'] = ($in_free_plan) ? 1 : 0;
          if (! isset($key[$namespace]['max']) && $in_plan_amount) $key[$namespace]['max'] = ($in_free_plan_default_amount) ? $in_free_plan_default_amount : 0;
          if ($extra_plan_config_boolean && ! empty($extra_plan_config_boolean)) {
            foreach($extra_plan_config_boolean as $config => $value) {
              if (! isset($key[$namespace][$config])) $key[$namespace][$config] = $value;
            }
          }
          if ($extra_plan_config_string && ! empty($extra_plan_config_string)) {
            foreach($extra_plan_config_string as $config => $value) {
              if (! isset($key[$namespace][$config])) $key[$namespace][$config] = $value;
            }
          }
        }
      }
    }

    return $key;
  }
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  public function getNameAttribute($value) {
    return ($this->id == 1) ? trans('global.full_access') : $value;
  }

  public function getDates() {
    return array('created_at', 'updated_at');
  }

  public function reseller() {
    return $this->belongsTo('\App\Reseller');
  }

  public function users() {
    return $this->belongsToMany('\App\User');
  }
}
