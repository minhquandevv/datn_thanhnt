<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateSkill extends Model
{
    protected $fillable = ['candidate_id', 'skill_name', 'skill_desc'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}