<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\JobCategory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    protected $model = \App\Models\JobOffer::class;

    public function definition()
    {
        return [
            'job_name' => $this->faker->jobTitle,
            'company_id' => Company::factory(),
            'job_category_id' => JobCategory::factory(),
            'expiration_date' => $this->faker->date,
            'job_detail' => $this->faker->paragraph,
            'job_description' => $this->faker->paragraph,
            'job_requirement' => $this->faker->paragraph,
            'job_position' => $this->faker->word,
            'job_salary' => $this->faker->randomFloat(2, 300, 5000),
            'job_quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}