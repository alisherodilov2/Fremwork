<?php

use App\Middleware\AuthMiddleware;
use App\Routing\Routing;

//use Framework\Routing\Router;
$router = new Routing();

// Route with middleware
$router->get('/dashboard', 'DashboardController@index', [AuthMiddleware::class]);

// Route without middleware
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/error/{code}', 'error\\ErrorController@error');

return $router;
