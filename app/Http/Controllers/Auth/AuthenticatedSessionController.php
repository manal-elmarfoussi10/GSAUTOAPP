<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des champs
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tentative de connexion
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'E-mail ou mot de passe incorrect.');
        }

        // Connexion réussie → régénération de la session
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirection selon le rôle
        switch ($user->role) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');
        
            case 'admin':
                return redirect()->route('dashboard');

            case 'planner':
            case 'poseur':
                return redirect()->route('dashboard.poseur'); // calendrier

            case 'comptable':
                return redirect()->route('comptable.dashboard'); // dashboard comptable

            case 'commercial':
                return redirect()->route('commercial.dashboard'); // dashboard commercial

            case 'client_service':
            case 'client_limited':
                return redirect()->route('emails.inbox'); // boîte mail

            default:
                return redirect()->route('dashboard'); // fallback
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
