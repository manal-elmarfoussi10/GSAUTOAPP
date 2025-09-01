<?php
// app/Http/Middleware/CheckCompanyAccess.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Email;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // For client routes
        if ($request->route('client')) {
            $client = Client::findOrFail($request->route('client'));
            if ($client->company_id !== auth()->user()->company_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // For email routes
        if ($request->route('email')) {
            $email = Email::findOrFail($request->route('email'));
            if ($email->company_id !== auth()->user()->company_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}