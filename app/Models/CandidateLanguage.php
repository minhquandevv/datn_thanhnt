<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateLanguage extends Model
{
    protected $fillable = ['candidate_id', 'name'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}