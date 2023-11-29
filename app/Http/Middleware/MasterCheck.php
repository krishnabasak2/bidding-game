<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MasterCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->cookie('master_id')) {
            $response = Helper::master_check($request->cookie('master_id'));
            $response = json_decode($response, true);

            if ($response['status'] === true) {
                return $next($request);
            } else {
                return redirect('logout');
            }
        } else {
            return redirect('logout');
        }
    }
}
