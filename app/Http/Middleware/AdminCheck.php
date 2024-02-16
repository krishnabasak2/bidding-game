<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('admin') && Session::get('admin')->role == '0') {
            if ($request->cookie('master_id')) {
                $master = Helper::master_check($request->cookie('master_id'));
                $master = json_decode($master, true);

                if ($master['status'] === true) {
                    return $next($request);
                } else {
                    return redirect('logout');
                }
            }

            $admin = User::select('status')->find(Session::get('admin')->id);
            if ($admin->status == '1') {
                return $next($request);
            } else {
                return redirect('logout');
            }
        } else {
            return redirect('logout');
        }
    }
}
