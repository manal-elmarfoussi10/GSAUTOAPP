<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreGlobalUserRequest;  // ✅ add
use App\Http\Requests\SuperAdmin\UpdateGlobalUserRequest; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GlobalUserController extends Controller
{
    public function index()
    {
        // Only global support roles (no company_id)
        $users = User::whereNull('company_id')
            ->whereIn('role', ['client_service','client_limited', 'superadmin'])
            ->orderBy('role')->orderBy('name')
            ->paginate(20);

        return view('superadmin.global_users.index', compact('users'));
    }

    public function create()
    {
        // Include superadmin in available roles
        $roles = [
            \App\Models\User::ROLE_CLIENT_SERVICE => 'Service client',
            \App\Models\User::ROLE_CLIENT_LIMITED => 'Service client limité',
            \App\Models\User::ROLE_SUPERADMIN     => 'Super Administrateur',
        ];
        return view('superadmin.global_users.create', compact('roles'));
    }
    
    public function store(StoreGlobalUserRequest $request)
    {
        $v = $request->validated();
    
        $user = new \App\Models\User();
        $user->first_name = $v['first_name'];
        $user->last_name  = $v['last_name'];
        $user->name       = $v['first_name'].' '.$v['last_name'];
        $user->email      = $v['email'];
        $user->password   = \Illuminate\Support\Facades\Hash::make($v['password']);
        $user->role       = $v['role'];                 // may be 'superadmin'
        $user->company_id = null;                       // GLOBAL user
        $user->is_active  = (bool)($v['is_active'] ?? true);
        $user->save();
    
        return redirect()->route('superadmin.global-users.index')
            ->with('success', 'Utilisateur global créé.');
    }

    public function edit(User $global_user)
    {
        // Only allow editing global support roles / superadmins with company_id = null
        abort_if(!is_null($global_user->company_id), 404);

        $roles = [
            User::ROLE_CLIENT_SERVICE => 'Service client',
            User::ROLE_CLIENT_LIMITED => 'Service client limité',
            User::ROLE_SUPERADMIN     => 'Super Administrateur',
        ];

        return view('superadmin.global_users.edit', [
            'user'  => $global_user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateGlobalUserRequest $request, User $global_user)
    {
        abort_if(!is_null($global_user->company_id), 404);

        $v = $request->validated();

        // Safety: don't disable/delete last active superadmin
        if (($global_user->role === User::ROLE_SUPERADMIN) && ($v['role'] !== User::ROLE_SUPERADMIN || !(bool)($v['is_active'] ?? true))) {
            $activeSupers = User::whereNull('company_id')->where('role', User::ROLE_SUPERADMIN)->where('is_active', true)->count();
            if ($activeSupers <= 1) {
                return back()->withErrors(['role' => 'Impossible de rétrograder/désactiver le dernier SuperAdmin actif.'])->withInput();
            }
        }

        $global_user->first_name = $v['first_name'];
        $global_user->last_name  = $v['last_name'];
        $global_user->name       = $v['first_name'].' '.$v['last_name'];
        $global_user->email      = $v['email'];
        $global_user->role       = $v['role'];
        $global_user->is_active  = (bool)($v['is_active'] ?? false);

        if (!empty($v['password'])) {
            $global_user->password = Hash::make($v['password']);
        }

        $global_user->save();

        return redirect()->route('superadmin.global-users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $global_user)
    {
        abort_if(!is_null($global_user->company_id), 404);

        // Prevent deleting yourself
        if (auth()->id() === $global_user->id) {
            return back()->withErrors(['user' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        // Prevent deleting the last active superadmin
        if ($global_user->role === User::ROLE_SUPERADMIN) {
            $activeSupers = User::whereNull('company_id')->where('role', User::ROLE_SUPERADMIN)->where('is_active', true)->count();
            if ($activeSupers <= 1) {
                return back()->withErrors(['user' => 'Impossible de supprimer le dernier SuperAdmin actif.']);
            }
        }

        $global_user->delete();

        return redirect()->route('superadmin.global-users.index')->with('success', 'Utilisateur supprimé.');
    }
}