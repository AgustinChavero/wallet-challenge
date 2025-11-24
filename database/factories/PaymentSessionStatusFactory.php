<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentSessionStatusFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => strtoupper($this->faker->unique()->randomElement([
                'PENDING',
                'APPROVED',
                'REJECTED',
            ])),
        ];
    }
}
