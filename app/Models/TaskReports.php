<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Intern;


class TaskReports extends Model
{
    protected $primaryKey = 'report_id';
    protected $guarded = ['report_id'];
    
    protected $fillable = [
        'task_id', 'report_date', 'work_done', 'next_day_plan'
    ];
    // Relationship với task
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    // Relationship với TTS (thông qua task)
    public function intern()
    {
        return $this->hasOneThrough(
            Intern::class,
            Task::class,
            'task_id', // Foreign key on tasks table
            'intern_id', // Foreign key on interns table
            'task_id', // Local key on task_reports table
            'intern_id' // Local key on tasks table
        );
    }
}