<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;

class DummyApplicationsSeeder extends Seeder
{
    public function run()
    {
        $job = Job::first();
        $applicant = User::where('role', 1)->first();

        if ($job && $applicant) {
            Application::create([
                'job_id' => $job->id,
                'user_id' => $applicant->id,
                'cv_path' => 'demos/cv1.pdf',
                'cover_letter' => 'I am interested in this role.',
                'status' => 'submitted',
            ]);

            // another application
            Application::create([
                'job_id' => $job->id,
                'user_id' => $applicant->id,
                'cv_path' => 'demos/cv2.pdf',
                'cover_letter' => 'Please find my application attached.',
                'status' => 'submitted',
            ]);
        }
    }
}
