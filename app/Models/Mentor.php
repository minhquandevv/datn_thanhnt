<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mentor extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'mentors';
    protected $primaryKey = 'mentor_id';

    protected $fillable = [
        'mentor_name',
        'username',
        'password',
        'department',
        'position',
        'status',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function interns()
    {
        return $this->hasMany(Intern::class, 'mentor_id', 'mentor_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_by', 'mentor_id');
    }
} 