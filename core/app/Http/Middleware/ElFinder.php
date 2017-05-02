<?php

namespace App\Http\Middleware;

use Closure;
use \Platform\Controllers\Core;

class ElFinder
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    /*
     |--------------------------------------------------------------------------
     | Set ElFinder config
     |--------------------------------------------------------------------------
     */
  
    $url_current = str_replace('https://', '', str_replace('http://', '', url()->current()));
    $reset_url = request()->server('HTTP_HOST') . '/reset/' . config('app.key');

    if ($url_current != $reset_url) {

      if (\Auth::guard('web')->check()) {
        $user_dir = Core\Secure::staticHash(\Auth::user()->id);
  
        $roots = array(
            array(
              'driver'    => 'Flysystem',
              'autoload'    => true,
              'filesystem'  => new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local(public_path() . '/uploads/')),
              'path'      => $user_dir,
              'URL'       => url('/uploads/' . $user_dir),
              'accessControl' => 'access',
              'tmpPath'     => public_path() . '/uploads/' . $user_dir . '/.tmp',
              'tmbURL'    => url('uploads/' . $user_dir . '/.tmb'),
              'tmbPath'     => public_path() . '/uploads/' . $user_dir . '/.tmb',
              'uploadMaxSize' => '4M',
              'tmbSize'     => '100',
              'tmbCrop'     => false,
              'icon'      => url('assets/packages/elfinder/img/volume_icon_local.png'),
              'alias'     => trans('global.my_files'),
              'uploadDeny'  => array('text/x-php'),
              'attributes' => array(
                array(
                   'pattern' => '/.tmb/',
                   'read' => false,
                   'write' => false,
                   'hidden' => true,
                   'locked' => false
                ),
                array(
                   'pattern' => '/.quarantine/',
                   'read' => false,
                   'write' => false,
                   'hidden' => true,
                   'locked' => false
                ),
                array( // hide readmes
                  'pattern' => '/\.(txt|html|php|py|pl|sh|xml)$/i',
                  'read'   => false,
                  'write'  => false,
                  'locked' => true,
                  'hidden' => true
                )
              )
            )/*,
            array(
            'driver'    => 'Flysystem',
            'path'      => public_path() . '/stock',
            'URL'       => '/stock',
            'defaults'     => array('read' => false, 'write' => false),
            'alias'     => trans('global.stock'),
            'tmbSize'     => '100',
            'tmbCrop'     => false,
            'icon'      => url('assets/packages/elfinder/img/volume_icon_image.png'),
            'attributes' => array(
              array(
                'pattern' => '!^.!',
                'hidden'  => false,
                'read'  => true,
                'write'   => false,
                'locked'  => true
              ),
              array(
                'pattern' => '/.tmb/',
                 'read' => false,
                 'write' => false,
                 'hidden' => true,
                 'locked' => false
              ),
              array(
                'pattern' => '/.quarantine/',
                 'read' => false,
                 'write' => false,
                 'hidden' => true,
                 'locked' => false
              )
            )
          )*/
        );
  
        app()->config->set('elfinder.roots', $roots);
      }
    }

    return $next($request);
  }
}
