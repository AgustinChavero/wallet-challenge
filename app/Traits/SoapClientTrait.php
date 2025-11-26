<?php

namespace App\Traits;

trait SoapClientTrait
{
    /**
     * Crea una instancia del SoapClient
     */
    protected function soap()
    {
        return new \SoapClient(null, [
            'location' => 'http://nginx/soap',
            'uri'      => 'http://nginx/soap',
            'trace'    => 1,
            'exceptions' => true
        ]);
    }

    /**
     * Formatea la respuesta del SOAP a JSON
     */
    protected function formatResponse($response)
    {
        $data = json_decode($response->data, true);

        return response()->json([
            'success' => $response->success,
            'cod_error' => $response->cod_error,
            'message_error' => $response->message_error,
            'data' => $data
        ], $response->success ? 200 : 400);
    }

    /**
     * Formatea respuesta de error genérica
     */
    protected function errorResponse($message, $code = '10', $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'cod_error' => $code,
            'message_error' => $message,
            'data' => null
        ], $statusCode);
    }
}
