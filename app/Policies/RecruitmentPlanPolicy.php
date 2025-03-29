<?php

namespace App\Policies;

use App\Models\RecruitmentPlan;
use App\Models\User;

class RecruitmentPlanPolicy
{
    public function view(User $user, RecruitmentPlan $recruitmentPlan)
    {
        return in_array($user->role, ['admin', 'hr']) && 
            ($user->role === 'admin' || $recruitmentPlan->created_by === $user->id);
    }

    public function approve(User $user, RecruitmentPlan $recruitmentPlan)
    {
        return $user->role === 'admin' && $recruitmentPlan->status === 'pending';
    }

    public function reject(User $user, RecruitmentPlan $recruitmentPlan)
    {
        return $user->role === 'admin' && $recruitmentPlan->status === 'pending';
    }

    public function submit(User $user, RecruitmentPlan $recruitmentPlan)
    {
        return $user->role === 'hr' && 
            $recruitmentPlan->created_by === $user->id && 
            $recruitmentPlan->status === 'draft';
    }
} 