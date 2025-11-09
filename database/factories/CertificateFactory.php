<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    protected $model = Certificate::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stream_name' => fake()->name(),
            'property_id' => Property::factory(),
            'issue_date' => date("Y-m-d"),
            'next_due_date' => date("Y-m-d", strtotime(date("Y-m-d") ." + 10 days"))
        ];
    }
}
