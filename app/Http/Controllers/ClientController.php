<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RegisterClientRequest;
use App\Traits\SoapClientTrait;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use SoapClientTrait;

    public function register(RegisterClientRequest $request): JsonResponse
    {
        try {
            $response = $this->soap()->registerClient(
                $request->document,
                $request->names,
                $request->email,
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
