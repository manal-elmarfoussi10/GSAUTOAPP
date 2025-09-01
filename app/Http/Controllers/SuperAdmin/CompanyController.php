<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreCompanyRequest;
use App\Models\Company;
use App\Http\Requests\SuperAdmin\UpdateCompanyRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(12);
        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        // roles for the optional “create first user” select (exclude superadmin)
        $roles = collect(User::roles())->except(User::ROLE_SUPERADMIN)->toArray();
        return view('superadmin.companies.create', compact('roles'));
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, &$company) {
            // 1) Create company
            $company = Company::create([
                'name'  => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                // … add other fields you want from $data here
            ]);

            // 2) Optionally create first user (admin or any role)
            if (!empty($data['create_admin'])) {
                $a = $data['admin'];
                $user = new User();
                $user->first_name = $a['first_name'];
                $user->last_name  = $a['last_name'];
                $user->name       = $a['first_name'].' '.$a['last_name'];
                $user->email      = $a['email'];
                $user->password   = Hash::make($a['password']);
                $user->role       = $a['role']; // e.g. 'admin'
                $user->company_id = $company->id;
                $user->is_active  = isset($a['is_active']) ? (bool)$a['is_active'] : true;
                $user->save();
            }
        });

        return redirect()
            ->route('superadmin.companies.show', $company)
            ->with('success', 'Société créée avec succès.');
    }

    public function show(Company $company)
    {
        $users = $company->users()->orderBy('role')->get();
        return view('superadmin.companies.show', compact('company','users'));
    }

    public function edit(\App\Models\Company $company)
{
    return view('superadmin.companies.edit', compact('company'));
}

public function update(UpdateCompanyRequest $request, \App\Models\Company $company)
{
    $validated = $request->validated();
    $company->fill($validated)->save();

    return redirect()->route('superadmin.companies.show', $company)
                     ->with('success', 'Société mise à jour.');
}

public function destroy(\App\Models\Company $company)
{
    // Optional: cascade delete users
    $company->users()->delete();

    $company->delete();

    return redirect()->route('superadmin.companies.index')
                     ->with('success', 'Société supprimée avec succès.');
}
}
