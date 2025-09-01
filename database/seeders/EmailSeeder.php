<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Email;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        Email::factory()->count(20)->create();
    }
}