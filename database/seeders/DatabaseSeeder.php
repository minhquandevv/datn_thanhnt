<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Worker;
use App\Models\Application;
use App\Models\Intern;
use App\Models\Company;
use App\Models\Department;
use App\Models\JobCategory;
use App\Models\JobSkill;
use App\Models\JobBenefit;
use App\Models\JobOffer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        // \App\Models\Worker::factory(10)->create();
        // \App\Models\Application::factory(10)->create();
        // \App\Models\Intern::factory(10)->create();
        // \App\Models\Company::factory(10)->create();
        // \App\Models\Department::factory(10)->create();
        // \App\Models\JobCategory::factory(10)->create();
        // \App\Models\JobSkill::factory(10)->create();
        // \App\Models\JobBenefit::factory(10)->create();
        // \App\Models\JobOffer::factory(10)->create();
    }
}