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
        Company::factory(5)->create()->each(function ($company) {
            // Mỗi công ty có từ 2-5 phòng ban
            Department::factory(rand(2, 5))->create(['company_id' => $company->id]);
        });

        // Seed dữ liệu cho bảng job_categories
        JobCategory::factory(5)->create();

        // Seed dữ liệu cho bảng job_skills
        JobSkill::factory(10)->create();

        // Seed dữ liệu cho bảng job_benefits
        JobBenefit::factory(5)->create();

        // Seed dữ liệu cho bảng job_offers
        JobOffer::factory(10)->create()->each(function ($jobOffer) {
            // Gán random skills cho job_offer
            $skills = \App\Models\JobSkill::inRandomOrder()->limit(rand(2, 5))->pluck('id');
            $jobOffer->skills()->attach($skills);

            // Gán random benefits cho job_offer
            $benefits = \App\Models\JobBenefit::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $jobOffer->benefits()->attach($benefits);
        });
    }
}