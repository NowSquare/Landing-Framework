<?php
namespace Modules\EmailCampaigns\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Email extends Model {
  use NodeTrait;

  protected $table = 'emails';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function emailCampaign() {
    return $this->belongsTo('Modules\EmailCampaigns\Http\Models\EmailCampaign', 'email_campaign_id');
  }

  public function forms() {
    return $this->belongsToMany('Modules\Forms\Http\Models\Form', 'email_forms', 'email_id', 'form_id');
  }

  /**
   * Get page url.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeUrl($query) {

    $local_domain = 'ec/' . $this->local_domain;

    if ($this->domain == '') {
      return url($local_domain);
    } else {
      return '//' . $this->domain;
    }
  }
}