<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add(
    'login',
    new Route(
        '/login', [
        '_controller' => [\App\Controller\Admin\AuthenticationController::class, 'loginAction'],
    ], [], [], '', [], ['POST']
    )
);

return $routes;