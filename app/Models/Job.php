<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $table = 'laraveljobs';

    protected $fillable = [
        'title',
        'description',
        'employer_id',
        'status',
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
}
