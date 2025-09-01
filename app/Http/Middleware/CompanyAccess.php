<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if (!$user->company_id) {
            return redirect('/')->with('error', 'Aucune entreprise associée à votre compte.');
        }

        // Optional: restrict certain roles
        // if (!in_array($user->role, ['admin', 'client_support'])) {
        //     return redirect('/')->with('error', 'Accès non autorisé.');
        // }

        return $next($request);
    }
}