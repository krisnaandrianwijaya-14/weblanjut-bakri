<?php

namespace Database\Factories;

use App\Models\Guardian;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->name(),
            'relation'   => fake()->randomElement(['ayah', 'ibu', 'wali']),
            'phone'      => '08' . fake()->numerify('##########'),
            'email'      => fake()->safeEmail(),
            'address'    => fake()->address(),
            'occupation' => fake()->jobTitle(),
        ];
    }
}
