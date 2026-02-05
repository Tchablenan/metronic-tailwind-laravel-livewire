<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ServiceRequestAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Vérifier si c'est un secrétaire OU un médecin chef
        if ($user->role === 'secretary' || ($user->role === 'doctor' && $user->is_chief)) {
            return $next($request);
        }

        abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
    }
}
