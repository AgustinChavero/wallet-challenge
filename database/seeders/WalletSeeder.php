<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        Client::all()->each(function ($client) {
            Wallet::create([
                'client_id' => $client->id,
                'balance' => 0,
            ]);
        });
    }
}
