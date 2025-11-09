<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => 'Property',
            'model_id' => Property::factory(),
            'note' => fake()->sentence()
        ];
    }

    public function model($model)
    {
        $modelName = array_search(get_class($model), Relation::morphMap()) ?: get_class($model);

        return $this->state(fn () => [
            'model_type' => $modelName,
            'model_id' => $model->id,
        ]);
    }
}
