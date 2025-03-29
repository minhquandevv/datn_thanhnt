<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    protected $primaryKey = 'cv_id';

    protected $fillable = [
        'candidate_id',
        'position_id',
        'status'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function position()
    {
        return $this->belongsTo(RecruitmentPosition::class, 'position_id');
    }
} 