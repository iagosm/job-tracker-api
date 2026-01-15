<?php

namespace Database\Factories;

use App\Models\Candidatura;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Feedback::class;
    public function definition(): array
    {
        return [
            'candidatura_id' => Candidatura::factory(),
            'tipo' => $this->faker->randomElement(['empresa','pessoal']),
            'feedback' => $this->faker->paragraph(),
            'nota' => $this->faker->optional()->numberBetween(1, 5),
            'pontos_fortes' => $this->faker->optional()->sentence(),
            'pontos_fracos' => $this->faker->optional()->sentence(),
            'aprendizados' => $this->faker->optional()->sentence(),
        ];
    }
}
