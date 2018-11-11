<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\AdminRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProductController
 *
 * @package App\Controller\Admin
 */
class ProductController extends AdminController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * ProductController constructor.
     *
     * @param AdminRepository $adminRepository
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     */
    public function __construct(
        AdminRepository $adminRepository,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ) {
        parent::__construct($adminRepository);

        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createOneAction(Request $request): JsonResponse
    {
        try {
            $this->checkAdminAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $barcode = $request->request->get('barcode');
        $name = $request->request->get('name');
        $cost = $request->request->get('cost');
        $vatClass = $request->request->get('vat_class');

        if (is_null($barcode) || is_null($name) || is_null($cost) || is_null($vatClass)) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        $cost = intval($cost);
        if ($cost <= 0) {
            return $this->json(['message' => 'Invalid value for `cost` property'], Response::HTTP_BAD_REQUEST);
        }

        if (false === in_array($vatClass, array_keys(Product::VAT_CLASS_NAMES))) {
            return $this->json(['message' => 'Invalid value for `vat class` property'], Response::HTTP_BAD_REQUEST);
        }

        $productWithSameBarcode = $this->productRepository->findOneByBarcode($barcode);
        if (false === is_null($productWithSameBarcode)) {
            return $this->json(['message' => 'Product with same barcode already exists'], Response::HTTP_CONFLICT);
        }

        $productWithSameName = $this->productRepository->findOneByName($name);
        if (false === is_null($productWithSameName)) {
            return $this->json(['message' => 'Product with same name already exists'], Response::HTTP_CONFLICT);
        }

        $product = new Product();
        $product
            ->setBarcode($barcode)
            ->setName($name)
            ->setCost($cost)
            ->setVatClass(Product::VAT_CLASS_NAMES[$vatClass]);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getListAction(Request $request): JsonResponse
    {
        try {
            $this->checkAdminAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $offset = $request->query->get('offset', 0);
        $limit = $request->query->get('limit', 15);

        if (false === is_numeric($offset) || false === is_numeric($limit)) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        if (intval($offset) < 0 || intval($limit) < 0) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        $products = $this->productRepository->getProducts($offset, $limit);
        $result = [
            'offset' => $offset,
            'limit' => $limit,
            'data' => [],
        ];

        foreach ($products as $product) {
            $vatClassName = array_search($product->getVatClass(), Product::VAT_CLASS_NAMES);
            $result['data'][] = [
                'barcode' => $product->getBarcode(),
                'name' => $product->getName(),
                'cost' => $product->getCost(),
                'vat_class' => $vatClassName,
            ];
        }

        return $this->json($result, Response::HTTP_OK);
    }
}
