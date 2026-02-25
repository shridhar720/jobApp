<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DummyUsersSeeder extends Seeder
{
    public function run()
    {
        // Employer
        User::create([
            'name' => 'Employer One',
            'email' => 'employer1@example.com',
            'role' => 2,
            'password' => bcrypt('password123'),
        ]);

        // Applicant
        User::create([
            'name' => 'Applicant One',
            'email' => 'applicant1@example.com',
            'role' => 1,
            'password' => bcrypt('password123'),
        ]);

        // Another applicant
        User::create([
            'name' => 'Applicant Two',
            'email' => 'applicant2@example.com',
            'role' => 1,
            'password' => bcrypt('password123'),
        ]);
    }
}
