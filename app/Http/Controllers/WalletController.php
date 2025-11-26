<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\BalanceWalletRequest;
use App\Http\Requests\Wallet\RechargeWalletRequest;
use App\Traits\SoapClientTrait;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    use SoapClientTrait;

    public function recharge(RechargeWalletRequest $request): JsonResponse
    {
        try {
            $response = $this->soap()->rechargeWallet(
                $request->document,
                $request->phone,
                $request->amount
            );

            return $this->formatResponse($response);
        } catch (\SoapFault $e) {
            return $this->errorResponse('SOAP Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function balance(BalanceWalletRequest $request): JsonResponse
    {
        try {
            $response = $this->soap()->checkBalance(
                $request->document,
                $request->phone
            );

            return $this->formatResponse($response);
        } catch (\SoapFault $e) {
            return $this->errorResponse('SOAP Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
