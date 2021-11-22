<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminAuth\AdminLoginController;

class AdminMiddleware
{
    
    protected $login;

    function __construct(AdminLoginController $login)
    {
        Auth::shouldUse('admin');
        $this->login = $login;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);
        Auth::guard('admin');

         if(!Auth::guard('admin')->check()) {
             if (!strstr($request->url(), 'login')) {
                 return redirect()->route('admin.login');
             }
         }

         return $next($request);
    }
}
