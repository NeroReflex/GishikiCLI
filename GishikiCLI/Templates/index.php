<?php

require __DIR__.'/vendor/autoload.php';

use Gishiki\Core\Route;
use Gishiki\HttpKernel\Request;
use Gishiki\HttpKernel\Response;
use Gishiki\Algorithms\Collections\SerializableCollection;
use Gishiki\Gishiki;

Route::get("/", function (Request &$reques, Response &$response) {
    $result = new SerializableCollection([
        "timestamp" => time()
    ]);

    //send the response to the client
    $response->setSerializedBody($result);
});

Route::any(Route::NOT_FOUND, function (Request &$request, Response &$response) {
    $result = new SerializableCollection([
        "error" => "Not Found",
        "timestamp" => time()
    ]);

    //send the response to the client'.PHP_EOL.
    $response->setSerializedBody($result);
});
//this triggers the framework execution
Gishiki::run();
