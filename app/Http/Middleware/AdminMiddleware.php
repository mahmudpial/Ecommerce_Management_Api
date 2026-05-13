<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user exists and has a role
        if ($user && $user->role) {
            $role = $user->role->name;

            // Allow both Admin and Manager for general dashboard/order tasks
            if ($role === 'Admin' || $role === 'Manager') {

                /* Optional: Strict Check
                   If the request is a DELETE method, only allow 'Admin'
                */
                if ($request->isMethod('delete') && $role !== 'Admin') {
                    return response()->json([
                        'message' => 'Unauthorized. Only Super Admin can delete resources.'
                    ], 403);
                }

                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Access Denied. You do not have the required permissions.'
        ], 403);
    }
}