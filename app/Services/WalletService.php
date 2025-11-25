<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Wallet;
use App\Models\WalletMovement;
use App\Models\WalletMovementType;
use App\Models\PaymentSession;
use App\Models\PaymentSessionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class WalletService
{
    public function registerClient($document, $names, $email, $phone)
    {
        return DB::transaction(function () use ($document, $names, $email, $phone) {
            if (Client::where('document', $document)->exists()) {
                throw new Exception("Client already exists with this document");
            }

            if (Client::where('email', $email)->exists()) {
                throw new Exception("Client already exists with this email");
            }

            $client = Client::create([
                'document' => $document,
                'names' => $names,
                'email' => $email,
                'phone' => $phone,
            ]);

            $wallet = Wallet::create([
                'client_id' => $client->id,
                'balance' => 0,
            ]);

            return [
                'client_id'  => $client->id,
                'document'   => $client->document,
                'names'      => $client->names,
                'email'      => $client->email,
                'phone'      => $client->phone,
                'wallet_id'  => $wallet->id,
                'balance'    => (float) $wallet->balance
            ];
        });
    }

    public function rechargeWallet($document, $phone, $amount)
    {
        return DB::transaction(function () use ($document, $phone, $amount) {
            $client = Client::where('document', $document)
                ->where('phone', $phone)
                ->first();

            if (!$client) {
                throw new Exception("Client not found");
            }

            if ($amount <= 0) {
                throw new Exception("Amount must be positive");
            }

            $wallet = $client->wallet;

            if (!$wallet) {
                throw new Exception("Wallet not found");
            }

            $wallet->balance += $amount;
            $wallet->save();

            $movementType = WalletMovementType::where('code', 'RECHARGE')->first();

            WalletMovement::create([
                'wallet_id' => $wallet->id,
                'type_id'   => $movementType->id,
                'amount'    => $amount,
            ]);

            return [
                'wallet_id' => $wallet->id,
                'new_balance' => (float) $wallet->balance,
                'amount_recharged' => (float) $amount
            ];
        });
    }

    public function initiatePayment($document, $phone, $amount)
    {
        return DB::transaction(function () use ($document, $phone, $amount) {
            $client = Client::where('document', $document)
                ->where('phone', $phone)
                ->first();

            if (!$client) {
                throw new Exception("Client not found");
            }

            if ($amount <= 0) {
                throw new Exception("Amount must be positive");
            }

            $wallet = $client->wallet;

            if (!$wallet) {
                throw new Exception("Wallet not found");
            }

            if ($wallet->balance < $amount) {
                throw new Exception("Insufficient balance");
            }

            $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $sessionUid = Str::uuid()->toString();

            $pendingStatus = PaymentSessionStatus::where('code', 'PENDING')->first();

            $session = PaymentSession::create([
                'wallet_id'  => $wallet->id,
                'session_uid' => $sessionUid,
                'token'      => $token,
                'amount'     => $amount,
                'status_id'  => $pendingStatus->id,
            ]);

            return [
                'session_id' => $session->session_uid,
                'message' => 'Please check your email for the confirmation token',
                'expires_in_minutes' => 10,
            ];
        });
    }

    public function confirmPayment($sessionId, $token)
    {
        return DB::transaction(function () use ($sessionId, $token) {
            $pending = PaymentSessionStatus::where('code', 'PENDING')->first();
            $confirmed = PaymentSessionStatus::where('code', 'CONFIRMED')->first();
            $expired = PaymentSessionStatus::where('code', 'EXPIRED')->first();
            $failed = PaymentSessionStatus::where('code', 'FAILED')->first();

            $session = PaymentSession::where('session_uid', $sessionId)
                ->where('status_id', $pending->id)
                ->first();

            if (!$session) {
                throw new Exception("Invalid or already processed session");
            }

            if (Carbon::now()->gt($session->created_at->addMinutes(10))) {
                $session->update(['status_id' => $expired->id]);
                throw new Exception("Token expired");
            }

            if ($session->token !== $token) {
                return DB::transaction(function () use ($session, $failed) {
                    $session->update(['status_id' => $failed->id]);
                    throw new Exception("Invalid token");
                });
            }

            $wallet = $session->wallet;

            if ($wallet->balance < $session->amount) {
                $session->update(['status_id' => $failed->id]);
                throw new Exception("Insufficient balance");
            }

            $wallet->balance -= $session->amount;
            $wallet->save();

            $movementType = WalletMovementType::where('code', 'PURCHASE')->first();

            WalletMovement::create([
                'wallet_id' => $wallet->id,
                'type_id'   => $movementType->id,
                'amount'    => -$session->amount,
            ]);

            $session->update([
                'status_id' => $confirmed->id,
                'confirmed_at' => Carbon::now(),
            ]);

            return [
                'session_id' => $session->session_uid,
                'amount_paid' => (float) $session->amount,
                'new_balance' => (float) $wallet->balance
            ];
        });
    }

    public function checkBalance($document, $phone)
    {
        $client = Client::where('document', $document)
            ->where('phone', $phone)
            ->first();

        if (!$client) {
            throw new Exception("Client not found");
        }

        $wallet = $client->wallet;

        if (!$wallet) {
            throw new Exception("Wallet not found");
        }

        return [
            'document' => $client->document,
            'names'    => $client->names,
            'email'    => $client->email,
            'balance'  => (float) $wallet->balance
        ];
    }
}
