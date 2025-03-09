<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'school_id',
        'cv',
        'status',
        'active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
