<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id'     => 'INV-' . now()->format('YmdHis') . '-' . Str::random(6),
            'amount'       => 350000,
            'paid_amount'  => 350000,
            'payment_type' => 'qris',
            'status'       => 'recorded',
            'recorded_at'  => now(),
        ];
    }
}
