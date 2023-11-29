<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
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
            $response = Helper::customer_check();
            $response = json_decode($response, true);

            if ($response && $response['status'] === true) {

                if ($request->cookie('master_id')) {
                    $master = Helper::master_check($request->cookie('master_id'));
                    $master = json_decode($master, true);

                    if ($master['status'] === true) {
                        return $next($request);
                    } else {
                        return redirect('logout');
                    }
                }

                return $next($request);
            } else {
                return redirect('logout');
            }
        } else {
            return redirect('logout');
        }
    }
}
