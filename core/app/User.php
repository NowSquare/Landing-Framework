<?php

namespace App;

use \Platform\Controllers\Core;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class User extends Authenticatable implements StaplerableInterface
{
  use Notifiable;
  use EloquentTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password', 'api_token',
  ];

  protected $casts = [
    'settings' => 'json'
  ];

  public function __construct(array $attributes = array()) {
      $this->hasAttachedFile('avatar', [
          'styles' => [
              'default' => '128x128#'
          ]
      ]);

      parent::__construct($attributes);
  }

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  public function getDates() {
    return array('created_at', 'updated_at', 'last_login', 'expires', 'trial_ends_at');
  }

  public function getFreePlanAttribute() {
    return ($this->attributes['plan_id'] == NULL) ? true : false;
  }

  public function getPlanNameAttribute() {
    if ($this->attributes['plan_id'] != NULL) {
      return $this->plan->name;
    } else {
      // Check for default plan (will be used instead of free)
      //$default_plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 1)->first();
      $default_plan = \App\Plan::where('active', 1)->where('default', 1)->first();
      return (empty($default_plan)) ? trans('global.free') : $default_plan->name;
    }
  }

  public function getPlanIdAttribute() {
    //return ($this->attributes['plan_id'] != NULL) ? $this->attributes['plan_id'] : 0;

    if ($this->attributes['plan_id'] != NULL) {
      return $this->attributes['plan_id'];
    } else {
      // Check for default plan (will be used instead of free)
      //$default_plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 1)->first();
      $default_plan = \App\Plan::where('active', 1)->where('default', 1)->first();
      return (empty($default_plan)) ? NULL : $default_plan->id;
    }
  }

  public function getAvatar() {
    if ($this->avatar_file_name != NULL) {
      return $this->avatar->url('default');
    } else {
      return \Avatar::create(strtoupper($this->name))->toBase64();
    }
  }

  public function hasRole($roles) {
    return in_array($this->role, $roles);
  }

  public function parentUser() {
    return $this->belongsTo('\App\User', 'parent_id');
  }

  public function childUsers() {
    return $this->hasMany('\App\User', 'parent_id');
  }

  public function plan() {
    if ($this->attributes['plan_id'] == null) {
      // Check for default plan (will be used instead of free)
      //$default_plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 1)->first();
      $default_plan = \App\Plan::where('active', 1)->where('default', 1)->first();
      if (! empty($default_plan)) $this->attributes['plan_id'] = $default_plan->id;
    }

    return $this->belongsTo('\App\Plan', 'plan_id');
  }

  public function reseller() {
    return $this->hasOne('\App\Reseller', 'reseller_id');
  }

  public function funnels() {
    return $this->hasMany('\Platform\Models\Funnels\Funnel', 'user_id');
  }

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token) {
      $this->notify(new ResetPasswordNotification($token));
  }
}