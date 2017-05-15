<?php
namespace Modules\LandingPages\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model {
  use NodeTrait;

  protected $table = 'landing_pages';

  protected $casts = [
    'meta' => 'json',
    'meta_published' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function site() {
    return $this->belongsTo('Modules\LandingPages\Http\Models\Site', 'landing_site_id');
  }
}