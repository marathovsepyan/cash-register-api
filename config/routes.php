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

$routes->add(
    'create_product',
    new Route(
        '/products', [
        '_controller' => [\App\Controller\Admin\ProductController::class, 'createOneAction'],
    ], [], [], '', [], ['POST']
    )
);

$routes->add(
    'list_products',
    new Route(
        '/products', [
        '_controller' => [\App\Controller\Admin\ProductController::class, 'getListAction'],
    ], [], [], '', [], ['GET']
    )
);

$routes->add(
    'cash_register_identify',
    new Route(
        '/cash-register/identify', [
        '_controller' => [\App\Controller\CashRegister\AuthenticationController::class, 'loginAction'],
    ], [], [], '', [], ['POST']
    )
);

$routes->add(
    'get_product',
    new Route(
        '/products/{barcode}', [
        '_controller' => [\App\Controller\CashRegister\ProductController::class, 'getOneAction'],
    ], [], [], '', [], ['GET']
    )
);

return $routes;