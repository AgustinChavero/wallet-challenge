<?php

namespace App\Services;

use App\Services\WalletService;

class WalletSoapService
{
    protected $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    private function okResponse($message, $data = [])
    {
        $obj = new \stdClass();
        $obj->success = true;
        $obj->cod_error = '00';
        $obj->message_error = $message;

        $obj->data = json_encode($data);

        return $obj;
    }

    private function failResponse($code, $message)
    {
        $obj = new \stdClass();
        $obj->success = false;
        $obj->cod_error = $code;
        $obj->message_error = $message;
        $obj->data = null;
        return $obj;
    }

    public function registerClient($document, $names, $email, $phone)
    {
        try {
            $result = $this->walletService->registerClient($document, $names, $email, $phone);
            return $this->okResponse('Client registered successfully', $result);
        } catch (\Exception $e) {
            return $this->failResponse('01', $e->getMessage());
        }
    }

    public function rechargeWallet($document, $phone, $amount)
    {
        try {
            $result = $this->walletService->rechargeWallet($document, $phone, $amount);
            return $this->okResponse('Wallet recharged successfully', $result);
        } catch (\Exception $e) {
            return $this->failResponse('02', $e->getMessage());
        }
    }

    public function initiatePayment($document, $phone, $amount)
    {
        try {
            $result = $this->walletService->initiatePayment($document, $phone, $amount);
            return $this->okResponse('Payment initiated. Token sent to email.', $result);
        } catch (\Exception $e) {
            return $this->failResponse('03', $e->getMessage());
        }
    }

    public function confirmPayment($sessionId, $token)
    {
        try {
            $result = $this->walletService->confirmPayment($sessionId, $token);
            return $this->okResponse('Payment confirmed successfully', $result);
        } catch (\Exception $e) {
            return $this->failResponse('04', $e->getMessage());
        }
    }

    public function checkBalance($document, $phone)
    {
        try {
            $result = $this->walletService->checkBalance($document, $phone);
            return $this->okResponse('Balance retrieved successfully', $result);
        } catch (\Exception $e) {
            return $this->failResponse('05', $e->getMessage());
        }
    }
}
