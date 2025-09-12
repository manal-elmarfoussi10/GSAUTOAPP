<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CompanyAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Middleware\CheckCompanyAccess;
use App\Http\Middleware\SuperAdminAccess;

use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\GlobalUserController;
use App\Http\Controllers\SuperAdmin\CompanyController as SuperAdminCompanyController;

use App\Http\Controllers\{
    ProfileController,
    ClientController,
    RdvController,
    DevisController,
    FactureController,
    PaiementController,
    AvoirController,
    FournisseurController,
    ProduitController,
    PoseurController,
    StockController,
    BonDeCommandeController,
    EmailTemplateController,
    EmailController,
    CompanyController,
    SidexaController,
    UserController,
    UnitController,
    ExpenseController,
    ContactController,
    DashboardController,
    DashboardPoseurController,
    AccountController,
    ConversationController,
    SearchController // 🔎 Import ajouté
};

// =====================================
// Utilitaires
// =====================================
Route::get('/attachment/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) abort(404);
    return response()->file($fullPath);
})->where('path', '.*')->name('attachment');

Route::get('/', fn () => redirect()->route('login'));

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHTML('<h1>Hello PDF</h1>');
    return $pdf->download('test.pdf');
});

// =====================================
// Auth simple
// =====================================
Route::middleware(['auth'])->group(function () {
    Route::get('/poseur/dashboard', [PoseurController::class, 'dashboard'])->name('poseur.dashboard');
    Route::post('/poseur/intervention/{id}/commenter', [PoseurController::class, 'commenter'])->name('poseur.commenter');

    Route::get('/mon-compte', [AccountController::class, 'show'])->name('mon-compte');
    Route::post('/mon-compte', [AccountController::class, 'update'])->name('mon-compte.update');
    Route::post('/mon-compte/mot-de-passe', [AccountController::class, 'updatePassword'])->name('mon-compte.password');
    Route::delete('/mon-compte/supprimer', [AccountController::class, 'destroy'])->name('mon-compte.delete');
    Route::post('/mon-compte/supprimer-photo', [AccountController::class, 'deletePhoto'])->name('mon-compte.photo.delete');
});

// =====================================
// Auth + CompanyAccess
// =====================================
Route::middleware(['auth', CompanyAccess::class])->group(function () {

    // 🔎 Recherche
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/poseur', [DashboardPoseurController::class, 'index'])->name('dashboard.poseur');

    // Poseur - Interventions
    Route::get('/poseur/dossiers', [DashboardPoseurController::class, 'dossiers'])->name('poseur.dossiers');
    Route::post('/poseur/intervention/{id}/comment', [DashboardPoseurController::class, 'ajouterCommentaire'])->name('poseur.comment');

    // Paiements
    Route::get('/paiements/create/{facture_id}', [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');
    Route::resource('paiements', PaiementController::class);

    // ✅ Clients (une seule resource)
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/export-pdf', [ClientController::class, 'exportPdf'])->name('clients.export.pdf');
    Route::post('/clients/{client}/statut-interne', [ClientController::class, 'updateStatutInterne'])->name('clients.statut_interne');

    // Conversations
    Route::post('clients/{client}/conversations', [ConversationController::class, 'store'])->name('clients.conversations.store');
    Route::post('/clients/{client}/conversation', [ConversationController::class, 'sendMessage'])->name('conversations.send');
    Route::get('/clients/{client}/conversation', [ConversationController::class, 'show'])->name('clients.conversation'); // ✅ corrigé
    Route::post('/conversations/reply/{email}', [ConversationController::class, 'reply'])->name('conversations.reply');
    Route::delete('conversations/{thread}', [ConversationController::class, 'destroyThread'])->name('conversations.destroyThread');
    Route::get('conversations/download/{reply}', [ConversationController::class, 'download'])->name('conversations.download');
    Route::get('conversations/fetch/{client}', [ConversationController::class, 'fetch'])->name('conversations.fetch');
    Route::get('/replies/{reply}/download', [ConversationController::class, 'download'])->name('conversations.download.reply');

    // RDV
    Route::get('/calendar', [RdvController::class, 'calendar'])->name('rdv.calendar');
    Route::get('/calendar/events', [RdvController::class, 'events'])->name('rdv.events');
    Route::resource('rdv', RdvController::class)->except(['create', 'edit', 'show']);

    // Devis
    Route::resource('devis', DevisController::class);
    Route::get('/devis/export/excel', [DevisController::class, 'exportExcel'])->name('devis.export.excel');
    Route::get('/devis/export/pdf', [DevisController::class, 'exportPDF'])->name('devis.export.pdf');
    Route::post('/devis/{devis}/generate-facture', [DevisController::class, 'generateFacture'])->name('devis.generate.facture');
    Route::get('/devis/{id}/pdf', [DevisController::class, 'downloadSinglePdf'])->name('devis.download.pdf');

    // Factures
    Route::resource('factures', FactureController::class);
    Route::get('/factures/export/excel', [FactureController::class, 'exportExcel'])->name('factures.export.excel');
    Route::get('/factures/export/pdf', [FactureController::class, 'exportFacturesPDF'])->name('factures.export.pdf');
    Route::get('/factures/{id}/pdf', [FactureController::class, 'downloadPdf'])->name('factures.download.pdf');
    Route::match(['get', 'post'], '/factures/{facture}/acquitter', [FactureController::class, 'acquitter'])->name('factures.acquitter');

    // Avoirs
    Route::get('/avoirs/export/excel', [AvoirController::class, 'exportExcel'])->name('avoirs.export.excel');
    Route::get('/avoirs/export/pdf', [AvoirController::class, 'exportPDF'])->name('avoirs.export.pdf');
    Route::get('/avoirs/{avoir}/pdf', [AvoirController::class, 'exportPDF'])->name('avoirs.pdf');
    Route::get('/avoirs/create/from-facture/{facture}', [AvoirController::class, 'createFromFacture'])->name('avoirs.create.fromFacture');
    Route::resource('avoirs', AvoirController::class);

    // Divers
    Route::resources([
        'fournisseurs' => FournisseurController::class,
        'produits' => ProduitController::class,
        'poseurs' => PoseurController::class,
        'stocks' => StockController::class,
        'expenses' => ExpenseController::class,
    ]);
    Route::get('/stocks/export/excel', [StockController::class, 'exportExcel'])->name('stocks.export.excel');
    Route::get('/stocks/export/pdf', [StockController::class, 'exportPDF'])->name('stocks.export.pdf');
    Route::get('/expenses/export/excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
    Route::get('/expenses/export/pdf', [ExpenseController::class, 'exportPDF'])->name('expenses.export.pdf');

    // Bons de commande
    Route::resource('bons-de-commande', BonDeCommandeController::class)->parameters(['bons-de-commande' => 'bon']);
    Route::get('bons-de-commande/export/excel', [BonDeCommandeController::class, 'exportExcel'])->name('bons-de-commande.export.excel');
    Route::get('bons-de-commande/export/pdf', [BonDeCommandeController::class, 'exportPDF'])->name('bons-de-commande.export.pdf');

    // Emails
    Route::resource('email-templates', EmailTemplateController::class)->only(['index', 'store', 'show']);
    Route::get('/email-templates', [EmailTemplateController::class, 'inbox'])->name('email-templates.inbox');

    Route::prefix('emails')->controller(EmailController::class)->group(function () {
        Route::get('/', 'inbox')->name('emails.inbox');
        Route::get('/sent', 'sent')->name('emails.sent');
        Route::get('/important', 'important')->name('emails.important');
        Route::get('/bin', 'bin')->name('emails.bin');
        Route::get('/create', 'create')->name('emails.create');
        Route::get('/notifications', 'notifications')->name('emails.notifications');
        Route::post('/mark-all-read', 'markAllRead')->name('emails.markAllRead');
        Route::post('/', 'store')->name('emails.store');
        Route::get('/{id}', 'show')->name('emails.show');
        Route::post('/{id}/delete', 'destroy')->name('emails.delete');
        Route::post('/{id}/restore', 'restore')->name('emails.restore');
        Route::post('/{id}/toggle-star', 'toggleStar')->name('emails.toggleStar');
        Route::delete('/{id}/permanent', 'permanentDelete')->name('emails.permanentDelete');
        Route::post('/{id}/toggle-important', 'toggleImportant')->name('emails.toggleImportant');
        Route::post('/{email}/mark-important', 'markImportant')->name('emails.markImportant');
        Route::post('/{email}/move-to-trash', 'moveToTrash')->name('emails.moveToTrash');
        Route::get('/{email}/reply', 'reply')->name('emails.reply');           // GET
        Route::post('/{id}/reply', 'reply')->name('emails.reply.send');        // ✅ renommé
        Route::delete('/{email}', 'destroy')->name('emails.destroy');
        Route::post('/upload', 'upload')->name('emails.upload');
    });

    // Profil entreprise
    Route::get('/profile', [CompanyController::class, 'show'])->name('company.profile');
    Route::get('/profile/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/profile/update', [CompanyController::class, 'update'])->name('company.update');
    Route::get('/profile/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/profile', [CompanyController::class, 'store'])->name('company.store');

    // Sidexa
    Route::prefix('sidexa')->controller(SidexaController::class)->group(function () {
        Route::get('/', 'index')->name('sidexa.index');
        Route::get('/create', 'create')->name('sidexa.create');
        Route::post('/', 'store')->name('sidexa.store');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Vues simples
    Route::get('/acheter-unites', [UnitController::class, 'showPurchaseForm'])->name('units.form');
    Route::post('/acheter-unites', [UnitController::class, 'purchase'])->name('units.purchase');
    Route::get('/ma-consommation', fn () => view('consommation.index'))->name('consommation.index');
    Route::view('/depenses', 'depenses.index')->name('depenses.index');
    Route::view('/fonctionnalites', 'fonctionnalites.fonctionnalites');
    Route::view('/commercial', 'commercial.dashboard')->name('commercial.dashboard');
    Route::view('/comptable', 'comptable.dashboard')->name('comptable.dashboard');
});

// =====================================
// Contact
// =====================================
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// =====================================
// SuperAdmin
// =====================================
Route::prefix('superadmin')
    ->middleware(['auth', SuperAdminAccess::class])
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('companies', SuperAdminCompanyController::class)
            ->only(['index','create','store','show','edit','update','destroy']);

        Route::get('companies/{company}/users/create',  [\App\Http\Controllers\SuperAdmin\UserController::class, 'create'])->name('companies.users.create');
        Route::post('companies/{company}/users',        [\App\Http\Controllers\SuperAdmin\UserController::class, 'store'])->name('companies.users.store');
        Route::get('companies/{company}/users/{user}/edit', [\App\Http\Controllers\SuperAdmin\UserController::class, 'edit'])->name('companies.users.edit');
        Route::put('companies/{company}/users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'update'])->name('companies.users.update');
        Route::delete('companies/{company}/users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'destroy'])->name('companies.users.destroy');

        Route::resource('global-users', GlobalUserController::class)->only(['index','create','store','edit','update','destroy']);
    });

require __DIR__.'/auth.php';
