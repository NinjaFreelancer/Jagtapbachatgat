<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class customer_login
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
        if (!$request->session()->exists('customer_id')) {
            return redirect('/customer')->with('error', 'Please Log In First');
        }else{
            $customer_id = $request->session()->get('customer_id');
            $logdata = DB::select("SELECT * FROM `loan` WHERE `customer_id`= ? AND `status`=?", [$customer_id, 0]);
            if(count($logdata)!=0){
                $request->session()->put('is_loan_active', 1);
            }
        }
        return $next($request);
    }
}