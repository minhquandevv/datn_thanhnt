<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Intern;
use App\Models\Mentors;
use App\Models\TaskReports;


class Tasks extends Model
{
    protected $primaryKey = 'task_id';
    protected $guarded = ['task_id'];

    // Định nghĩa các giá trị enum cho status
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_OVERDUE = 'overdue';

    // Định nghĩa các giá trị enum cho evaluation
    const EVAL_EXCELLENT = 'excellent';
    const EVAL_GOOD = 'good';
    const EVAL_AVERAGE = 'average';
    const EVAL_POOR = 'poor';

    protected $fillable = [
        'intern_id', 'project_name', 'task_name', 'requirements', 'attachment',
        'assigned_date', 'assigned_by', 'status', 'result', 'mentor_comment', 'evaluation'
    ];

    // Relationship với TTS
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    // Relationship với mentor giao việc
    public function assignedBy()
    {
        return $this->belongsTo(Mentors::class, 'assigned_by');
    }

    // Relationship với báo cáo
    public function reports()
    {
        return $this->hasMany(TaskReports::class, 'task_id');
    }
}