<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'candidate_id', 'company_name', 'position',
        'date_start', 'date_end', 'description', 'is_working'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}