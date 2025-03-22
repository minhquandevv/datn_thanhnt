<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'email', 'identity_number', 'fullname', 'password', 'dob', 'location', 'image_company',
        'identity_image', 'gender', 'phone_number', 'address', 'experience_year', 'department_id',
        'candidate_profile_id', 'reference_name', 'reference_number', 'r_email', 'r_relate',
        'r_position', 't_company', 'url_avatar', 'finding_job'
    ];

    public function profile()
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_profile_id');
    }

    public function skills()
    {
        return $this->hasMany(CandidateSkill::class);
    }

    public function languages()
    {
        return $this->hasMany(CandidateLanguage::class);
    }

    public function desires()
    {
        return $this->hasOne(CandidateDesire::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}