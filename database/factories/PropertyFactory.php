<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'price' => fake()->numberBetween(10000000, 100000000),
            'ad_type' => fake()->randomElement(['sell', 'rent']),
            'is_active' => fake()->boolean(),
            'featured' => fake()->boolean(),
            'property_code' => fake()->unique()->numerify('P####'),
            'images' => json_encode([]),
            'settlement_id' => Settlement::factory(),
            'property_type_id' => PropertyType::factory(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the property is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the property is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the property is for sale.
     */
    public function forSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'ad_type' => 'sell',
        ]);
    }

    /**
     * Indicate that the property is for rent.
     */
    public function forRent(): static
    {
        return $this->state(fn (array $attributes) => [
            'ad_type' => 'rent',
        ]);
    }
}
