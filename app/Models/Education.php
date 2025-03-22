<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'candidate_id', 'level', 'edu_type', 'department',
        'school_name', 'graduate_level', 'graduate_date', 'is_main'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}