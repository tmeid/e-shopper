<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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

        // đăng nhập nhưng sai quyền, logout và redirect đăng nhập lại
        if(Auth::user()->role !== 1){
            Auth::logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
