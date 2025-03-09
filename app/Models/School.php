<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'address',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
