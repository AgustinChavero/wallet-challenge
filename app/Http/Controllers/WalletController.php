<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\BalanceWalletRequest;
use App\Http\Requests\Wallet\RechargeWalletRequest;

class WalletController extends Controller
{
    private function soap()
    {
        $soapUrl = 'http://nginx/soap';

        return new \SoapClient(null, [
            'location' => $soapUrl,
            'uri'      => $soapUrl,
            'trace'    => 1,
            'exceptions' => true
        ]);
    }

    public function recharge(RechargeWalletRequest $request)
    {
        try {
            $response = $this->soap()->rechargeWallet(
                $request->document,
                $request->phone,
                $request->amount
            );

            $data = json_decode($response->data, true);

            return response()->json([
                'success' => true,
                'cod_error' => '00',
                'message_error' => $response->message_error,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cod_error' => '10',
                'message_error' => $e->getMessage()
            ], 400);
        }
    }

    public function balance(BalanceWalletRequest $request)
    {
        try {
            $response = $this->soap()->checkBalance(
                $request->document,
                $request->phone
            );

            $data = json_decode($response->data, true);

            return response()->json([
                'success' => true,
                'cod_error' => '00',
                'message_error' => $response->message_error,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cod_error' => '10',
                'message_error' => $e->getMessage()
            ], 400);
        }
    }
}
