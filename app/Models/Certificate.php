<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'name',
        'date',
        'result',
        'location',
        'url_cert'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}