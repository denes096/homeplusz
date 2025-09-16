<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyType>
 */
class PropertyTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PropertyType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Lakás', 'Ház', 'Iroda', 'Üzlethelyiség', 'Telek']),
        ];
    }
}
