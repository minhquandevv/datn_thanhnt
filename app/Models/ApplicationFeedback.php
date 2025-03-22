<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'feedback',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}