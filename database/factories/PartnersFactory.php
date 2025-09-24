<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partners>
 */
class PartnersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'company' => fake()->company(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'country' => 'Magyarország',
            'contact_person' => fake()->name(),
            'contact_position' => fake()->jobTitle(),
            'notes' => fake()->text(),
            'status' => fake()->randomElement(['Aktív', 'Felfüggesztve', 'Archív']),
        ];
    }
}
