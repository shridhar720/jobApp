<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class DummyJobsSeeder extends Seeder
{
    public function run()
    {
        $employer = User::where('role', 2)->first();
        if (! $employer) {
            $employer = User::first();
        }

        Job::create([
            'user_id' => $employer->id,
            'title' => 'Frontend Developer',
            'description' => 'Build UI components',
            'type' => 'full-time',
            'status' => 'open',
        ]);

        Job::create([
            'user_id' => $employer->id,
            'title' => 'Backend Developer',
            'description' => 'Build APIs and background jobs',
            'type' => 'full-time',
            'status' => 'open',
        ]);
    }
}
