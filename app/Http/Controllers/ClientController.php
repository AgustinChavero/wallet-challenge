<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RegisterClientRequest;

class ClientController extends Controller
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

    public function register(RegisterClientRequest $request)
    {
        try {
            $response = $this->soap()->registerClient(
                $request->document,
                $request->names,
                $request->email,
                $request->phone
            );

            $data = json_decode($response->data, true);

            return response()->json([
                'success' => $response->success,
                'cod_error' => $response->cod_error,
                'message_error' => $response->message_error,
                'data' => $data
            ], $response->success ? 200 : 400);
        } catch (\SoapFault $e) {
            return response()->json([
                'success' => false,
                'cod_error' => '10',
                'message_error' => 'SOAP Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cod_error' => '10',
                'message_error' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
