<?php
namespace Modules\Eddystones\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {

  protected $table = 'eddystone_attachments';

  protected $casts = [
    'data' => 'json'
  ];

  public function eddystone() {
    return $this->belongsTo('Models\Eddystone');
  }
}