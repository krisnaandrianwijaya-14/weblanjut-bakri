<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    public function definition(): array
    {
        return [
            'name'          => 'Kelas ' . fake()->unique()->numerify('##'),
            'academic_year' => '2025/2026',
            'level'         => fake()->randomElement(['Ula', 'Wustho', 'Ulya']),
        ];
    }
}
