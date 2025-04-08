<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterviewSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'candidate_id',
        'interviewer_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'interview_type',
        'status',
        'notes',
        'meeting_link',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the candidate that owns the interview schedule.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the interviewer that owns the interview schedule.
     */
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
