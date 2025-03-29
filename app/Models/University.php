<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $primaryKey = 'university_id';

    protected $fillable = [
        'name',
        'representative_name',
        'representative_position',
        'phone',
        'email'
    ];

    public function recruitmentPlans()
    {
        return $this->belongsToMany(RecruitmentPlan::class, 'recruitment_plan_university', 'university_id', 'plan_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
} 