<?php

namespace App\Controller\CashRegister;

use App\Entity\Product;
use App\Repository\CashRegisterRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProductController
 *
 * @package App\Controller\CashRegister
 */
class ProductController extends CashRegisterController
{
    /** @var ProductRepository */
    private $productRepository;

    /**
     * ProductController constructor.
     *
     * @param CashRegisterRepository $cashRegisterRepository
     * @param ProductRepository      $productRepository
     */
    public function __construct(CashRegisterRepository $cashRegisterRepository, ProductRepository $productRepository)
    {
        parent::__construct($cashRegisterRepository);

        $this->productRepository = $productRepository;
    }

    /**
     * @param string  $barcode
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getOneAction(string $barcode, Request $request): JsonResponse
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $product = $this->productRepository->findOneByBarcode($barcode);

        if (is_null($product)) {
            return $this->json(['message' => 'Product with given barcode could not be found'], Response::HTTP_NOT_FOUND);
        }

        $vatClassName = array_search($product->getVatClass(), Product::VAT_CLASS_NAMES);

        $result = [
            'barcode' => $product->getBarcode(),
            'name' => $product->getName(),
            'cost' => $product->getCost(),
            'vat_class' => $vatClassName,
        ];

        return $this->json($result, Response::HTTP_OK);
    }
}
