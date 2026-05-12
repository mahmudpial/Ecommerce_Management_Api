<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Auth::check() এর বদলে সরাসরি $request->user() চেক করা যায়
        if ($request->user() && $request->user()->role?->name === 'Admin') {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized. Only Admin can access this.'
        ], 403);
    }
}