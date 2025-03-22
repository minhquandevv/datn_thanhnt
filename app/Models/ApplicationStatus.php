<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'status',
        'status_date',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}