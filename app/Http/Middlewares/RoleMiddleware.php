<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        
        if (!auth()->check()) {
            return redirect('/login'); 
        }

        $user = auth()->user();

        $userRole = strtolower($user->rol);

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Acceso denegado. No tienes los permisos necesarios.');
    }
}