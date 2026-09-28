<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'code' => 'MP-' . fake()->unique()->numerify('###'),
            'name' => fake()->randomElement(['Fiqih', 'Nahwu', 'Shorof', 'Tauhid', 'Tafsir', 'Hadits']),
        ];
    }
}
