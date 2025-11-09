<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation' => fake()->name(),
            'property_type' => fake()->word(),
            'parent_property_id' => fake()->word(),
            'uprn' => fake()->shuffleString("131531"),
            'address' => fake()->address,
            'town' => fake()->city(),
            'postcode' => fake()->postcode(),
            'live' => rand(0,1),
            'deleted_at' => null,
        ];
    }
}
