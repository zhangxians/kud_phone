<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Outbound
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
        if(Auth::user()->type===1){
            return $next($request);
        }elseif(Auth::user()->type===2){
            return redirect('sales');
        }else{
            return redirect('');
        }
    }
}
