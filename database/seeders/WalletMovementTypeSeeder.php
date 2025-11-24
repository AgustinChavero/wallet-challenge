<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WalletMovementType;

class WalletMovementTypeSeeder extends Seeder
{
    public function run(): void
    {
        if (WalletMovementType::count() >= 2) {
            return;
        }

        $types = [
            ['code' => 'RECHARGE', 'description' => 'Wallet recharge'],
            ['code' => 'PAYMENT',  'description' => 'Payment made from wallet'],
        ];

        foreach ($types as $type) {
            WalletMovementType::create($type);
        }
    }
}
