<?php

namespace Database\Factories;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        return [
            'description' => 'Syahriah SPP ' . now()->format('F Y'),
            'amount'      => 350000,
            'period'      => now()->format('Y-m'),
            'due_date'    => now()->addDays(10),
            'status'      => 'issued',
        ];
    }
}
