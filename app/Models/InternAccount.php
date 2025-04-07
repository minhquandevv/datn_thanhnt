<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternAccount extends Model
{
    protected $fillable = [
        'intern_id',
        'username',
        'email',
        'password_plain',
        'is_active'
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }
} 