<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'profile_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'profile_code',
        'url_cv'
    ];

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }
}