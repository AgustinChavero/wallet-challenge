<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
use App\Http\Requests\Payment\InitiatePaymentRequest;
use App\Traits\SoapClientTrait;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use SoapClientTrait;

    public function pay(InitiatePaymentRequest $request)
    {
        try {
            $response = $this->soap()->initiatePayment(
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

    public function confirm(ConfirmPaymentRequest $request): JsonResponse
    {
        try {
            $response = $this->soap()->confirmPayment(
                $request->session_id,
                $request->token
            );

            return $this->formatResponse($response);
        } catch (\SoapFault $e) {
            return $this->errorResponse('SOAP Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
