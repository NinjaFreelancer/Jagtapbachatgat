<?php

namespace App\Http\Middleware;

use Closure;

class send_email
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
        if (!$request->session()->exists('user')) {
            return redirect('/admin')->with('error', 'Please Log In First');
        }
        if (!$request->session()->exists('email')) {
            return redirect('/collection');
        }
        return $next($request);
    }
}