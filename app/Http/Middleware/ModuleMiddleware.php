<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        // You can use the $module parameter to perform module-specific logic here

        $user = auth()->user();

        //Admin can access everything
        if ($user->role === 'Admin') {
            return $next($request);
        }

        //Check module access
        if ($user->module !== $module) {
            abort(403, 'Unauthorized access to this module.');
        }
        return $next($request);
    }
}
