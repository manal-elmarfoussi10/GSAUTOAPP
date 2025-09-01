<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User; // <-- add this

class UpdateGlobalUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // Route model binding gives you a User instance here.
        // We must extract the numeric id for the unique rule.
        $routeParam = $this->route('global_user');
        $id = $routeParam instanceof User ? $routeParam->id : (int) $routeParam;

        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id, // <-- now it's an int
            'password'   => 'nullable|string|min:6|confirmed',
            'role'       => 'required|in:client_service,client_limited,superadmin',
            'is_active'  => 'nullable|boolean',
        ];
    }
}