<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   

  public function boot(): void
{
    // 🔧 Fix pour Hostinger : DomPDF ne comprend pas 'public_path()'
    $this->app->bind('path.public', function () {
        return base_path('public_html');
    });

    // 🛠️ Appliquer aussi DOMPDF_CHROOT manuellement
    config(['dompdf.defines.DOMPDF_CHROOT' => base_path('public_html')]);

    // (Optionnel) Forcer HTTPS si besoin
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        URL::forceScheme('https');
    }

config(['dompdf.defines.DOMPDF_CHROOT' => base_path('public_html')]);
    // Tes gates personnalisés
    Gate::define('access-client', fn($user) => in_array($user->role, ['admin', 'client_service', 'commercial']));
    Gate::define('access-dashboard', fn($user) => true);
    Gate::define('create-client', fn($user) => in_array($user->role, ['admin', 'client_service']));
    Gate::define('access-calendar', fn($user) => in_array($user->role, ['admin', 'poseur', 'commercial']));
    Gate::define('view-devis', fn($user) => in_array($user->role, ['admin', 'commercial', 'planner']));
    Gate::define('view-factures', fn($user) => in_array($user->role, ['admin', 'comptable']));
    Gate::define('view-avoirs', fn($user) => in_array($user->role, ['admin', 'comptable']));
    Gate::define('access-expenses', fn($user) => in_array($user->role, ['admin', 'planner', 'comptable']));
    Gate::define('view-orders', fn($user) => in_array($user->role, ['admin', 'planner']));
    Gate::define('is-superadmin', fn($user) => $user->role === 'superadmin');
}
}
