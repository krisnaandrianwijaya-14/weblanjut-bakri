<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'day_of_week' => fake()->randomElement(['senin', 'selasa', 'rabu', 'kamis', 'jumat']),
            'start_time'  => '07:30',
            'end_time'    => '09:00',
            'room'        => 'R-' . fake()->numerify('##'),
        ];
    }
}
