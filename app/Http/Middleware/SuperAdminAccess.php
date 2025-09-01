<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();
        if (!$u || $u->role !== 'superadmin') abort(403);
        return $next($request);
    }
}