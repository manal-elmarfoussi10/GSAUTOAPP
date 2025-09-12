<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Superadmin & Service client: full access
        if (in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_CLIENT_SERVICE], true)) {
            return $next($request);
        }

        // Others must belong to a company
        if (! $user->company_id) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // If route has {client}, enforce same company — but DON'T block when client.company_id is null
        if ($clientParam = $request->route('client')) {
            $client = $clientParam instanceof Client ? $clientParam : Client::find($clientParam);
            if ($client) {
                $clientCompanyId = $client->company_id;

                // Only enforce when the client is actually linked to a company
                if (!is_null($clientCompanyId) && (int)$clientCompanyId !== (int)$user->company_id) {
                    abort(403, 'Accès refusé (client d’une autre entreprise).');
                }
            }
        }

        // If route has {company}, enforce match
        if ($company = $request->route('company')) {
            $companyId = is_object($company) ? $company->id : (int) $company;
            if ((int)$companyId !== (int)$user->company_id) {
                abort(403, 'Accès refusé (mauvaise entreprise).');
            }
        }

        return $next($request);
    }
}