<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CompanyAccess;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PoseurController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BonDeCommandeController;
use App\Http\Controllers\BonDeCommandeLigneController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SidexaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPoseurController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ConversationController;

Route::get('/', fn () => redirect()->route('login'));

// Auth-only routes
Route::middleware(['auth', CompanyAccess::class])->group(function () {
    Route::get('/poseur/dashboard', [PoseurController::class, 'dashboard'])->name('poseur.dashboard');
    Route::post('/poseur/intervention/{id}/commenter', [PoseurController::class, 'commenter'])->name('poseur.commenter');

    Route::get('/mon-compte', [AccountController::class, 'show'])->name('mon-compte');
    Route::post('/mon-compte', [AccountController::class, 'update'])->name('mon-compte.update');
    Route::post('/mon-compte/mot-de-passe', [AccountController::class, 'updatePassword'])->name('mon-compte.password');
    Route::delete('/mon-compte/supprimer', [AccountController::class, 'destroy'])->name('mon-compte.delete');
    Route::post('/mon-compte/supprimer-photo', [AccountController::class, 'deletePhoto'])->name('mon-compte.photo.delete');
});

// Auth + company access routes
Route::middleware(['auth', CompanyAccess::class])->group(function () {
    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/poseur', [DashboardPoseurController::class, 'index'])->name('dashboard.poseur');
    Route::get('/poseur/dossiers', [DashboardPoseurController::class, 'dossiers'])->name('poseur.dossiers');
    Route::post('/poseur/intervention/{id}/comment', [DashboardPoseurController::class, 'ajouterCommentaire'])->name('poseur.comment');

    // Paiements
    Route::get('/paiements/create', [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');
    Route::resource('paiements', PaiementController::class);

    // Clients & Conversations
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/export-pdf', [ClientController::class, 'exportPdf'])->name('clients.export.pdf');
    Route::post('/clients/{client}/statut-interne', [ClientController::class, 'updateStatutInterne'])->name('clients.statut_interne');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');

    // Conversations
    Route::post('/clients/{client}/conversations', [ConversationController::class, 'store'])
        ->name('clients.conversations.store');
    Route::post('/conversations/reply/{email}', [ConversationController::class, 'reply'])
        ->name('conversations.reply');
    Route::delete('/conversations/{thread}', [ConversationController::class, 'destroyThread'])
        ->name('conversations.destroyThread');
    Route::get('/conversations/fetch/{client}', [ConversationController::class, 'fetch'])
        ->name('conversations.fetch');
    Route::get('/conversations/download/{reply}', [ConversationController::class, 'download'])
        ->name('conversations.download.reply');
    Route::get('/clients/{client}/conversation', [ConversationController::class, 'show'])
        ->name('clients.conversation');

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

    // Resources & exports
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

    Route::resource('bons-de-commande', BonDeCommandeController::class)
        ->parameters(['bons-de-commande' => 'bon']);
    Route::get('bons-de-commande/export/excel', [BonDeCommandeController::class, 'exportExcel'])
        ->name('bons-de-commande.export.excel');
    Route::get('bons-de-commande/export/pdf', [BonDeCommandeController::class, 'exportPDF'])
        ->name('bons-de-commande.export.pdf');

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
        Route::get('/{email}/reply', 'reply')->name('emails.reply');
        Route::delete('/{email}', 'destroy')->name('emails.destroy');
        Route::post('/upload', 'upload')->name('emails.upload');
    });

    // Company / profile
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

    // Units / consumption
    Route::get('/acheter-unites', [UnitController::class, 'showPurchaseForm'])->name('units.form');
    Route::post('/acheter-unites', [UnitController::class, 'purchase'])->name('units.purchase');

    Route::get('/ma-consommation', fn () => view('consommation.index'))->name('consommation.index');
    Route::view('/depenses', 'depenses.index')->name('depenses.index');
    Route::view('/fonctionnalites', 'fonctionnalites.fonctionnalites');
    Route::view('/commercial', 'commercial.dashboard')->name('commercial.dashboard');
    Route::view('/comptable', 'comptable.dashboard')->name('comptable.dashboard');

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
});

require __DIR__.'/auth.php';
