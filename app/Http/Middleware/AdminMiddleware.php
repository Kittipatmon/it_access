<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Allow if role is admin OR department is ICT (ID 16)
            if ($user->role === 'admin' || $user->dept_id == 16) {
                return $next($request);
            }
        }

        abort(403, 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
    }
}
