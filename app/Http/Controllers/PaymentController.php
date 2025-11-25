<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
use App\Http\Requests\Payment\InitiatePaymentRequest;

class PaymentController extends Controller
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

    public function pay(InitiatePaymentRequest $request)
    {
        try {
            $response = $this->soap()->initiatePayment(
                $request->document,
                $request->phone,
                $request->amount
            );

            $data = json_decode($response->data, true);

            return response()->json([
                'success' => true,
                'cod_error' => '00',
                'message_error' => $response->message_error,
                'data' => $data,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'cod_error' => '10',
                'message_error' => $e->getMessage()
            ], 400);
        }
    }

    public function confirm(ConfirmPaymentRequest $request)
    {
        try {
            $response = $this->soap()->confirmPayment(
                $request->session_id,
                $request->token
            );

            return response()->json([
                'success' => true,
                'cod_error' => '00',
                'message_error' => $response->message_error,
                'data' => $response
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
