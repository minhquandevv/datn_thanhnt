<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_name', 'department_id', 'expiration_date', 'job_detail', 
        'job_description', 'job_requirement', 'job_position', 'job_salary', 'job_quantity', 'job_category_id', 'status'
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(JobSkill::class, 'job_offer_job_skills');
    }

    public function benefits()
    {
        return $this->belongsToMany(JobBenefit::class, 'job_offer_job_benefits');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_offer_id');
    }
}