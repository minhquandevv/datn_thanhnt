<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateDesire extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'pay_from',
        'pay_to',
        'location'
    ];

    protected $casts = [
        'pay_from' => 'decimal:2',
        'pay_to' => 'decimal:2'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}