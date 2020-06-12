<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->name){
            return redirect()->route('admin_login');
        }
        
        return $next($request);
    }
}
