<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'dob',
        'address',
        'school_id',
        'experience_year',
        'cv',
        'status',
        'is_finding_job',
        'avatar',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
