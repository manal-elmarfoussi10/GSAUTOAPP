<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreCompanyUserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(Company $company)
    {
        $roles = collect(User::roles())->except(User::ROLE_SUPERADMIN)->toArray();
        return view('superadmin.users.create', compact('company','roles'));
    }

    public function store(StoreCompanyUserRequest $request, Company $company)
    {
        $v = $request->validated();

        $user = new User();
        $user->first_name = $v['first_name'];
        $user->last_name  = $v['last_name'];
        $user->name       = $v['first_name'].' '.$v['last_name'];
        $user->email      = $v['email'];
        $user->password   = Hash::make($v['password']);
        $user->role       = $v['role'];
        $user->company_id = $company->id;
        $user->is_active  = isset($v['is_active']) ? (bool)$v['is_active'] : true;
        $user->save();

        return redirect()->route('superadmin.companies.show', $company)
                         ->with('success', 'Utilisateur ajouté.');
    }

    public function destroy(\App\Models\Company $company, \App\Models\User $user)
{
    abort_if($user->company_id !== $company->id, 403);

    $user->delete();

    return redirect()->route('superadmin.companies.show', $company)
                     ->with('success', 'Utilisateur supprimé.');
}

public function update(Request $request, Company $company, User $user)
{
    abort_if($user->company_id !== $company->id, 403);

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email,' . $user->id,
        'role'       => 'required|in:admin,commercial,planner,poseur,comptable',
        'password'   => 'nullable|string|min:6|confirmed',
        'is_active'  => 'nullable|boolean',
    ]);

    // block global-only roles just in case
    if (in_array($validated['role'], ['client_service','client_limited','superadmin'])) {
        return back()->withErrors(['role' => 'Ce rôle est réservé au SuperAdmin.'])->withInput();
    }

    $user->first_name = $validated['first_name'];
    $user->last_name  = $validated['last_name'];
    $user->name       = $validated['first_name'].' '.$validated['last_name'];
    $user->email      = $validated['email'];
    $user->role       = $validated['role'];
    $user->is_active  = (bool)($validated['is_active'] ?? false);

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return redirect()->route('superadmin.companies.show', $company)
                     ->with('success', 'Utilisateur mis à jour.');
}

public function edit(Company $company, User $user)
{
    abort_if($user->company_id !== $company->id, 403);

    // Company-scoped roles (exclude global-only roles)
    $roles = [
        User::ROLE_ADMIN          => 'Administrateur',
        User::ROLE_COMMERCIAL     => 'Commercial',
        User::ROLE_PLANNER        => 'Service Devis, commande et RDV',
        User::ROLE_INSTALLER      => 'Poseur',
        User::ROLE_ACCOUNTANT     => 'Comptable',
        // Do NOT include: client_service, client_limited, superadmin
    ];

    return view('superadmin.users.edit', compact('company', 'user', 'roles'));
}
}
