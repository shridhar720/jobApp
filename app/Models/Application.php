<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job;

class Application extends Model
{
    protected $fillable = [
        'job_id', 'user_id', 'cv_path', 'cover_letter', 'status', 'reviewed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
