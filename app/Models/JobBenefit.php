<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobBenefit extends Model
{
    //
    use HasFactory;
    protected $fillable = ['title', 'description'];

    public function jobOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'job_offer_job_benefits');
    }
}
