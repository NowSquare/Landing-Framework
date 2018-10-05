<?php
namespace Modules\EmailCampaigns\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

  protected $table = 'email_events';

  protected $casts = [
    'meta' => 'json'
  ];

  public function email() {
    return $this->belongsTo('Models\Emails', 'email_id');
  }
}