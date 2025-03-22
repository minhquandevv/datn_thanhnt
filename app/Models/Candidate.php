<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'candidate_profile_id',
        'reference_name',
        'reference_number',
        'r_email',
        'r_relate',
        'r_position',
        'r_company',
        'url_avatar',
        'finding_job',
        'avatar',
        'id_card_front',
        'id_card_back',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
        'finding_job' => 'boolean',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
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

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Boot method to ensure relationships are loaded
    protected static function boot()
    {
        parent::boot();
        
        static::with([
            'profile',
            'department',
            'skills',
            'languages',
            'desires',
            'certificates',
            'education',
            'experience'
        ]);
    }
}