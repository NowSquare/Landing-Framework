<?php
namespace App\Traits;

/*
In model add:

  use App\Traits\Encryptable;

  ---

  use Encryptable;

  ---

  protected $encryptable = [
    'mail_username',
    'mail_password',
    'mail_mailgun_domain',
    'mail_mailgun_secret',
    'avangate_key',
  ];
*/

trait Encryptable
{
  public function getAttribute($key)
  {
    $value = parent::getAttribute($key);

    if (in_array($key, $this->encryptable)) {
      $value = ($value != null && trim($value) != '') ? decrypt($value) : null;
    }

     return $value;
  }

  public function setAttribute($key, $value)
  {
    if (in_array($key, $this->encryptable)) {
       $value = ($value != null && trim($value) != '') ? encrypt($value) : null;
    }

    return parent::setAttribute($key, $value);
  }
  
  private function encryptIfListed($key, $value)
  {
    if (in_array($key, $this->encryptable)) {
       $value = ($value != null && trim($value) != '') ? encrypt($value) : null;
    }

    return $value;
  }
}