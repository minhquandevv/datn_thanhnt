<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['candidate_id', 'name', 'date', 'result', 'location', 'url_cert'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}