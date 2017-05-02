<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\EmptyStringToNull;
use App\Traits\Encryptable;

class Reseller extends Model
{
  use EmptyStringToNull;
  use Encryptable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'resellers';

  protected $casts = [
    'settings' => 'json'
  ];

  protected $encryptable = [
    'mail_username',
    'mail_password',
    'mail_mailgun_domain',
    'mail_mailgun_secret',
    'avangate_key',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['domain'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  public function setAttribute($key, $value) {
    $value = $this->emptyStringToNull($value);
    $value = $this->encryptIfListed($key, $value);

    return parent::setAttribute($key, $value);
  }

  public function getDates() {
    return array('created_at', 'updated_at', 'expires');
  }

  public function users() {
    return $this->hasMany('\App\User');
  }
}
