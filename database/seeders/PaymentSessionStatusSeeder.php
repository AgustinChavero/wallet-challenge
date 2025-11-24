<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentSessionStatus;

class PaymentSessionStatusSeeder extends Seeder
{
    public function run()
    {
        if (PaymentSessionStatus::count() >= 2) {
            return;
        }

        $statuses = [
            'PENDING',
            'COMPLETED',
        ];

        foreach ($statuses as $code) {
            PaymentSessionStatus::firstOrCreate(['code' => $code]);
        }
    }
}
