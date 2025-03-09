<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle,
            'company' => $this->faker->company,
            'location' => $this->faker->city,
            'deadline' => $this->faker->date(),
            'requirements' => implode("\n", $this->faker->sentences(5)), // Tạo danh sách 5 yêu cầu
            'apply_link' => $this->faker->url,
            'description' => implode("\n", $this->faker->sentences(5)), // Tạo danh sách 5 mô tả công việc
            'status' => $this->faker->randomElement(['open', 'closed']),
        ];
    }

}
