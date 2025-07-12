<?php

namespace App\Http\Middleware;

use Closure;

class user_login
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
        if (!$request->session()->exists('acc_no')) {
            return redirect('/customer')->with('error', 'Please Log In First');
        }
        return $next($request);
    }
}