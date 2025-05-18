<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => \App\Models\User::factory(),
            'module' => $this->faker->randomElement(['FIT152', 'CTIP152', 'SEN152', 'IBS152', 'ACC152']),
            'topic' => $this->faker->word(),
            'question' => $this->faker->sentence(),
            'answer' => $this->faker->sentence(),
            'category' => $this->faker->word(),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'is_correct' => $this->faker->boolean(),
            'is_bookmarked' => $this->faker->boolean(),
        ];
    }
}
