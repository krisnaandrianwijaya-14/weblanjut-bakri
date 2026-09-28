<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'name'            => 'Ust. ' . fake()->name(),
            'employee_number' => 'GURU-' . fake()->unique()->numerify('####'),
            'phone'           => '08' . fake()->numerify('##########'),
            'status'          => 'active',
        ];
    }
}
