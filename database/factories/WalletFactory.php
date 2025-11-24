<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

class WalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
