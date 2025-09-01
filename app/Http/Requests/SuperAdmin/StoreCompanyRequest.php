<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:50',
            // optional docs/ids you already have can be added later
            // Optional: create first admin
            'create_admin' => 'sometimes|boolean',
            'admin.first_name' => 'required_if:create_admin,1|string|max:255',
            'admin.last_name'  => 'required_if:create_admin,1|string|max:255',
            'admin.email'      => 'required_if:create_admin,1|email|unique:users,email',
            'admin.password'   => 'required_if:create_admin,1|string|min:6|confirmed',
            'admin.role'       => 'required_if:create_admin,1|in:admin,client_service,client_limited,commercial,planner,poseur,comptable',
            'admin.is_active'  => 'nullable|boolean',
        ];
    }
}
