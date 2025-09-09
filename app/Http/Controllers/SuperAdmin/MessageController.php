<?php
// app/Http/Controllers/SuperAdmin/MessageController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        $hasCompanyId = Schema::hasColumn('contacts', 'company_id');

        $query = Contact::query()->latest();

        // Search by name/email/message
        if ($s = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }

        // Optional filter by company if the column exists
        if ($hasCompanyId && $request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        // Eager load company only if it exists
        if ($hasCompanyId) {
            $query->with('company:id,name');
        }

        $contacts = $query->paginate(20)->withQueryString();

        // Companies for the filter dropdown (only if column exists)
        $companies = $hasCompanyId ? Company::select('id', 'name')->orderBy('name')->get() : collect();

        return view('superadmin.messages.index', [
            'contacts'     => $contacts,
            'companies'    => $companies,
            'hasCompanyId' => $hasCompanyId,
            'filters'      => [
                'q'          => $s ?? '',
                'company_id' => $request->get('company_id'),
            ],
        ]);
    }

    public function show(Contact $message)
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        // Optional company preload if column exists
        if (Schema::hasColumn('contacts', 'company_id')) {
            $message->load('company:id,name');
        }

        return view('superadmin.messages.show', compact('message'));
    }

    public function destroy(Contact $message)
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        $message->delete();

        return redirect()
            ->route('superadmin.messages.index')
            ->with('success', 'Message supprimé.');
    }
}