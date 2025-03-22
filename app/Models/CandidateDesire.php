<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateDesire extends Model
{
    protected $fillable = ['candidate_id', 'pay_from', 'pay_to', 'location'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}