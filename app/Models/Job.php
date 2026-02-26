<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Application;

class Job extends Model
{
    use HasFactory;

    protected $table = 'laraveljobs';

    protected $fillable = [
        'title',
        'description',
        'employer_id',
        'status',
        'location',
        'salary_min',
        'salary_max',
        'type',
    ];

    // scope for open jobs
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}
