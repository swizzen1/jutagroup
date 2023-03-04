<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckIfSuper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::get('admin')->role !== 1) {
            return redirect()->route('AdminMainPage');
        }

        return $next($request);
    }
}
