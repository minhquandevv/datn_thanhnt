<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JobOffer;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'job_offer_id',
        'application_date',
        'status',
        'feedback'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_offer_id');
    }
}