<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) return redirect()->route('login');

        // nothing to enforce for superadmin
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        // If routes include a company in URL, ensure it matches user's company
        if ($request->route('company')) {
            $company = $request->route('company');
            $companyId = is_object($company) ? $company->id : (int)$company;

            if ((int)$user->company_id !== (int)$companyId) {
                abort(403, 'Accès refusé à cette société.');
            }
        }

        return $next($request);
    }
}