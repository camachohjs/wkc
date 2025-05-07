<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->current_session_id !== $request->session()->getId()) {
            /* Auth::logout(); */
            auth('web')->logout();
            return response()->json(['message' => 'Sesión cerrada debido a inicio de sesión desde otro lugar.'], 401);
        }

        return $next($request);
    }
}
