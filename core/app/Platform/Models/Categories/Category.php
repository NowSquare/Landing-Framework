<?php
namespace Platform\Models\Categories;

use Illuminate\Database\Eloquent\Model;

Class Category extends Model {

  protected $table = 'categories';

	// Disabling Auto Timestamps
  public $timestamps = false;

  public function reseller() {
    return $this->belongsTo('App\Reseller');
  }

  public function cards() {
    return $this->belongsToMany('Platform\Models\Location\Card', 'category_card', 'card_id', 'category_id');
  }
}