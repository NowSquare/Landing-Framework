<?php

namespace App\Http\Middleware;

use Closure;

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
      $lang = $request->input('lang', null);

      if ($request->user()) {
        if ($lang != null && $lang != $request->user()->language) {
          // Update language
          $request->user()->language = $lang;
          $request->user()->save();
        }
        app()->setLocale($request->user()->language);
      }

      return $next($request);
    }
}