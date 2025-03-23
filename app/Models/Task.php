<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'name',
        'description',
        'status',
        'deadline',
        'intern_id',
        'mentor_id'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship với intern
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    // Relationship với mentor
    public function mentor()
    {
        return $this->belongsTo(Mentors::class, 'mentor_id');
    }
} 