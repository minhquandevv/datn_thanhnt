<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'mentors';
    protected $primaryKey = 'mentor_id';

    protected $fillable = [
        'mentor_name',
        'department_id',
        'position',
        'username',
        'password',
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

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'mentor_id', 'mentor_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_by', 'mentor_id');
    }
} 