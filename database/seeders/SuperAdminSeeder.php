<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'super@gsauto.com'],
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'name'       => 'Super Admin',
                'password'   => Hash::make('super123'),
                'role'       => 'superadmin',
                'company_id' => null,
                'is_active'  => 1,
            ]
        );
    }
}