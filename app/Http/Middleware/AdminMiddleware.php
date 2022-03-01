<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\Auth\AdminLoginController as AuthAdminLoginController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    protected $login;

    function __construct(AuthAdminLoginController $login)
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

        if (!Auth::guard('admin')->check()) {
            if (!strstr($request->url(), 'login')) {
                return redirect()->route('admin.login');
            }
        }
        return $next($request);
    }
}
