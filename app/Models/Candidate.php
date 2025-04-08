<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'identity_number',
        'fullname',
        'password',
        'dob',
        'location',
        'image_company',
        'identity_image',
        'gender',
        'phone_number',
        'address',
        'experience_year',
        'department_id',
        'university_id',
        'candidate_profile_id',
        'reference_name',
        'reference_number',
        'r_email',
        'r_relate',
        'r_position',
        'r_company',
        'url_avatar',
        'finding_job',
        'active',
        'verification_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
        'finding_job' => 'boolean',
        'active' => 'boolean'
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function interviews()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function profile()
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_profile_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
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

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experience()
    {
        return $this->hasMany(Experience::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    public function cvs()
    {
        return $this->hasMany(CV::class);
    }

    // Boot method to ensure relationships are loaded
    protected static function boot()
    {
        parent::boot();
        
        static::with([
            'profile',
            'department',
            'university',
            'skills',
            'languages',
            'desires',
            'certificates',
            'education',
            'experience'
        ]);
    }
}