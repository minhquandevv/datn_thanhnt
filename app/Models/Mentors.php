<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Intern;
use App\Models\Task;

class Mentors extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'mentor_id';
    protected $guarded = ['mentor_id'];

    protected $fillable = [
        'mentor_name', 'department', 'position', 'username', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship với TTS
    public function interns()
    {
        return $this->hasMany(Intern::class, 'mentor_id');
    }

    // Relationship với công việc đã giao
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }
}