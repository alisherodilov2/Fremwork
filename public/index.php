<?php

use App\Http\Request;
use App\Http\Response;

require '../vendor/autoload.php';

$request = new Request();
$response = new Response();

$router = require '../routes/web.php';

ob_start();
$router->dispatch($request->uri, $request->method);
$output = ob_get_clean();

$response->setStatusCode(200);
$response->send($output);
