<?php

namespace App\Http\Middleware;

use Closure;
use \Platform\Controllers\Core;

class Localization
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $lang = $request->input('set_lang', null);

      if ($request->user()) {
        if ($lang != null && $lang != $request->user()->language) {
          // Update language
          $request->user()->language = $lang;
          $request->user()->save();
        }
        app()->setLocale($request->user()->language);
      } else {
        // No user logged in, use reseller default
        $reseller = Core\Reseller::get();
        app()->setLocale($reseller->default_language);
      }

      return $next($request);
    }
}