<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobCategory>
 */
class JobCategoryFactory extends Factory
{
    protected $model = \App\Models\JobCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
        ];
    }
}