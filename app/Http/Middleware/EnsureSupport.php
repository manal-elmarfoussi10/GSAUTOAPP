<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class EnsureSupport
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            // Not authenticated
            abort(401);
        }

        // Build the list of allowed roles (supports both constants and plain strings)
        $superadmin      = defined(User::class.'::ROLE_SUPERADMIN')
            ? User::ROLE_SUPERADMIN : 'superadmin';

        $clientService   = defined(User::class.'::ROLE_CLIENT_SERVICE')
            ? User::ROLE_CLIENT_SERVICE : 'client_service';

        $allowed = [$superadmin, $clientService];

        // Let superadmin & service client pass
        if (in_array($user->role, $allowed, true)) {
            return $next($request);
        }

        // Hide these routes from others
        abort(404);
    }
}