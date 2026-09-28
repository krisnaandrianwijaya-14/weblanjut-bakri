<?php

namespace Database\Factories;

use App\Models\Dormitory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DormitoryFactory extends Factory
{
    protected $model = Dormitory::class;

    public function definition(): array
    {
        return [
            'name'     => 'Gedung ' . fake()->unique()->firstName(),
            'gender'   => fake()->randomElement(['putra', 'putri']),
            'location' => fake()->address(),
        ];
    }
}
