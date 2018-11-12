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

$routes->add(
    'create_receipt',
    new Route(
        '/receipts', [
        '_controller' => [\App\Controller\CashRegister\ReceiptController::class, 'createOneActon'],
    ], [], [], '', [], ['POST']
    )
);

$routes->add(
    'finish_receipt',
    new Route(
        '/receipts/{id}/finish', [
        '_controller' => [\App\Controller\CashRegister\ReceiptController::class, 'finishOneAction'],
    ], [], [], '', [], ['PATCH']
    )
);

$routes->add(
    'add_product_to_receipt',
    new Route(
        '/receipts/{id}/products/{barcode}', [
        '_controller' => [\App\Controller\CashRegister\ReceiptController::class, 'addProductToReceiptAction'],
    ], [], [], '', [], ['POST']
    )
);

$routes->add(
    'change_receipt_last_product_amount',
    new Route(
        '/receipts/{id}/last-product-amount', [
        '_controller' => [\App\Controller\CashRegister\ReceiptController::class, 'changeLastProductAmountAction'],
    ], [], [], '', [], ['PATCH']
    )
);

$routes->add(
    'get_receipt_data',
    new Route(
        '/receipts/{id}', [
        '_controller' => [\App\Controller\CashRegister\ReceiptController::class, 'getOneAction'],
    ], [], [], '', [], ['GET']
    )
);

return $routes;