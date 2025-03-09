<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobSkill>
 */
class JobSkillFactory extends Factory
{
    protected $model = \App\Models\JobSkill::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
