<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CheckForSsl
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
    $ssl_check_cache = config('system.ssl_check_cache');

    if (\Request::server('HTTP_X_FORWARDED_PROTO') != 'https') {
      $url = request()->url();
      $url = str_replace ('http:' , 'https:', $url);
      $redirect = request()->fullUrl();
      $redirect = str_replace ('http:' , 'https:', $redirect);

      $status = \Cache::remember('ssl_check_' . md5($url), $ssl_check_cache, function () use($url) {
        $client = new Client();

        try {
          $result = $client->get($url);
          $status = $result->getStatusCode();
        } catch (\Exception $e) {
          $status = 404;
        }
        return $status;
      });

      if ($status != 404) {
        return redirect($redirect);
      }
    }

    return $next($request);
	}
}