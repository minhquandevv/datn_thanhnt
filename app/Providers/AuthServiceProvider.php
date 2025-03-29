<?php

namespace App\Providers;

use App\Models\RecruitmentPlan;
use App\Policies\RecruitmentPlanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        RecruitmentPlan::class => RecruitmentPlanPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 