<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // chưa đăng nhậP 
        if(Auth::guest()){
            return redirect()->route('login');
        }

        // admin truy cập vào trang của user
        if(Auth::user()->role !== 0){
            return redirect()->route('home');
        }
        return $next($request);
    }
}
