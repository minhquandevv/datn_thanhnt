<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCategory extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name'];

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class, 'job_category_id');
    }
}
