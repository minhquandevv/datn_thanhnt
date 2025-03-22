<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_offer_id',
        'cover_letter',
        'cv_path',
        'status',
        'applied_at'
    ];

    protected $casts = [
        'applied_at' => 'datetime'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }
}