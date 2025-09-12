<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait CompanyScoped
{
    public static function bootCompanyScoped(): void
    {
        // Automatically set company_id when creating
        static::creating(function ($model) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->role !== 'superadmin') {
                // Normal users: always assign their company_id
                $model->company_id = $user->company_id;
            }
            // Superadmin: leave company_id as is (can be NULL = global)
        });

        // Apply scope on queries
        static::addGlobalScope('company', function (Builder $builder) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->role === 'superadmin') {
                // Superadmin: no restriction (see everything)
                return;
            }

            // Normal users: see their company products + global (NULL)
            $builder->where(function ($q) use ($user) {
                $q->where('company_id', $user->company_id)
                  ->orWhereNull('company_id');
            });
        });
    }
}