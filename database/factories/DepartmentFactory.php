<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
// Department Factory
class DepartmentFactory extends Factory
{
    protected $model = \App\Models\Department::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'company_id' => Company::factory(),
        ];
    }
}