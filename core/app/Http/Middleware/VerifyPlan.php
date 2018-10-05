<?php

namespace App\Http\Middleware;

use Closure;

class VerifyPlan
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $limitation = null)
    {
      if ($limitation != null) {
        if ($request->user()->plan == null) {
          // Free plan
          if ($limitation != 'account.plan_visible') return response('Unauthorized.', 401);
        } else {
          $value = array_get($request->user()->plan->limitations, $limitation);

          if ($value == 0) {
            return response('Unauthorized.', 401);
          }
        }
      }

      return $next($request);
    }
}