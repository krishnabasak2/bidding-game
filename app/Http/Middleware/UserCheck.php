<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where(['id' => Auth::id(), 'status' => '1'])->first();
        // dd(Auth::id());
        if ($user) {
            return $next($request);
        } else {
            return response()->json(['staus' => false, 'message' => 'Account suspend.', 400]);
        }
    }
}
