<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeoserverLayer>
 */
class GeoserverLayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['point', 'polygon', 'line', 'raster']),
            'title' => $this->faker->sentence(3),
            'name' => $this->faker->slug(2),
            'openlayerUrl' => $this->faker->url,
        ];
    }
}
