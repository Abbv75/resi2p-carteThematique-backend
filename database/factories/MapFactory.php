<?php

namespace Database\Factories;

use App\Models\Thematique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Map>
 */
class MapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_thematique' => Thematique::factory(),
            'id_user' => User::factory(),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'downloadUrl' => $this->faker->url,
            'thumbnail' => $this->faker->imageUrl(),
        ];
    }
}
