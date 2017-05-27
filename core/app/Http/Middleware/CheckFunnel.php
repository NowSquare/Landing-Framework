<?php

namespace App\Http\Middleware;

use Closure;

class CheckFunnel
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
        $sl_funnel = session('funnel', '');

        if ($sl_funnel != '') {
          $qs = \Platform\Controllers\Core\Secure::string2array($sl_funnel);
          $funnel_id = $qs['funnel_id'];
          // Check if funnel exists
          $funnel = \Platform\Models\Funnels\Funnel::where('user_id', $request->user()->id)->where('id', $funnel_id)->first();

          if (empty($funnel)) {
            return response('Unauthorized.', 401);
          }
        } else {
          return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}