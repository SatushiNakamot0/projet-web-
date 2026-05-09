<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    // Kangériw la requête lli jaya bach nchoufo wesh l'utilisateur admin wla la
    public function handle(Request $request, Closure $next): Response
    {
        // Yla kan l'utilisateur machi admin, nrj3oh l'accueil wla n3tiweh erreur 403
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès refusé. Réservé aux administrateurs.');
        }

        return $next($request);
    }
}
