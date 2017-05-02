<?php
namespace Platform\Models\Landing;

use Illuminate\Database\Eloquent\Model;

Class Pages extends Model {

  protected $table = 'landing_pages';

  protected $casts = [
    'meta' => 'json'
  ];

	// Disabling Auto Timestamps
  public $timestamps = false;

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function reseller() {
    return $this->belongsTo('App\Reseller');
  }
}