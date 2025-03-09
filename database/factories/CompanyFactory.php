<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'amount_staff' => $this->faker->numberBetween(10, 500),
            'location' => $this->faker->address,
            'image_company' => $this->faker->imageUrl(),
        ];
    }
}