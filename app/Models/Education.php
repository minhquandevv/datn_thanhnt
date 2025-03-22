<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id', 'level', 'edu_type', 'department',
        'school_name', 'graduate_level', 'graduate_date', 'is_main'
    ];

    protected $casts = [
        'graduate_date' => 'date',
        'is_main' => 'boolean'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}