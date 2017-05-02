<?php
namespace Platform\Models\Members;

use App\Notifications\MemberResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

Class Member extends Authenticatable implements StaplerableInterface
{
  use Notifiable;
  use EloquentTrait;

  protected $table = 'members';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'reseller_id', 'user_id', 'name', 'email', 'password', 'confirmation_code'
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

  public function getDates()
  {
    return array('created_at', 'updated_at', 'last_login');
  }

  public function getAvatar() {
    if ($this->avatar_file_name != NULL) {
      return $this->avatar->url('default');
    } else {
      return \Avatar::create(strtoupper($this->name))->toBase64();
    }
  }

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token)
  {
      $this->notify(new MemberResetPassword($token, request()->get('sl')));
  }
}
