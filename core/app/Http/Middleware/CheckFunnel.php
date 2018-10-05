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
        $cookie_name = 'funnel' . \Platform\Controllers\Core\Secure::staticHash($request->user()->id);

        $sl_funnel = session($cookie_name, \Cookie::get($cookie_name, ''));

        if ($sl_funnel != '') {
          $qs = \Platform\Controllers\Core\Secure::string2array($sl_funnel);
          $funnel_id = $qs['funnel_id'];
          // Check if funnel exists
          $funnel = \Platform\Models\Funnels\Funnel::where('user_id', $request->user()->id)->where('id', $funnel_id)->first();

          if (empty($funnel)) {
            return redirect('platform/funnels');
            //return \App::make('\Platform\Controllers\App\FunnelController')->showFunnels();
            //return response('Unauthorized.', 401);
          }
        } else {
          return redirect('platform/funnels');
          //return \App::make('\Platform\Controllers\App\FunnelController')->showFunnels();
          //return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}