<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experience';

    protected $fillable = [
        'candidate_id', 'company_name', 'position',
        'date_start', 'date_end', 'description', 'is_working'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'is_working' => 'boolean'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}