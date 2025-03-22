<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    protected $primaryKey = 'profile_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['profile_code', 'url_cv'];

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'candidate_profile_id', 'profile_code');
    }
}