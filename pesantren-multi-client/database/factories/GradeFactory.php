<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        return [
            'academic_year' => '2025/2026',
            'component'     => fake()->randomElement(['tugas', 'kuis', 'uts', 'uas']),
            'score'         => fake()->numberBetween(70, 95),
            'notes'         => null,
        ];
    }
}
