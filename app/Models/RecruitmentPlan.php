<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentPlan extends Model
{
    use HasFactory;

    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'assigned_to',
        'start_date',
        'end_date',
        'description',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function universities()
    {
        return $this->belongsToMany(University::class, 'recruitment_plan_university', 'plan_id', 'university_id');
    }

    public function positions()
    {
        return $this->hasMany(RecruitmentPosition::class, 'plan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
} 