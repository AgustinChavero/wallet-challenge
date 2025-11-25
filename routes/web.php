<?php

use Illuminate\Support\Facades\Route;
use App\Services\WalletSoapService;

Route::any('/soap', function () {

    $server = new \SoapServer(null, [
        'uri' => url('/soap'),
    ]);

    $server->setClass(WalletSoapService::class);

    $server->handle();
});
