<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentPosition extends Model
{
    use HasFactory;

    protected $primaryKey = 'position_id';

    protected $fillable = [
        'plan_id',
        'name',
        'quantity',
        'requirements',
        'description',
        'department_id'
    ];

    public function plan()
    {
        return $this->belongsTo(RecruitmentPlan::class, 'plan_id');
    }

    public function cvs()
    {
        return $this->hasMany(CV::class, 'position_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
} 