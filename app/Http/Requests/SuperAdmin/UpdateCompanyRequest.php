<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Company;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $routeParam = $this->route('company');
        $id = $routeParam instanceof Company ? $routeParam->id : (int) $routeParam;

        return [
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:companies,email,'.$id,
            'phone' => 'nullable|string|max:50',
            // add more fields from your fillable if you put them on the form
        ];
    }
}